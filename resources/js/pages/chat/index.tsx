import React, { useState, useEffect, useRef, useCallback } from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip';
import {
    MessageSquare, Plus, Search, Send, Users, FolderOpen, User as UserIcon,
    MoreHorizontal, Hash, Check, CheckCheck
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { toast } from '@/components/custom-toast';

interface Participant {
    id: number;
    name: string;
    avatar: string | null;
}

interface LastMessage {
    message: string;
    sender_name: string;
    created_at: string;
}

interface Conversation {
    id: number;
    type: 'direct' | 'group' | 'project';
    name: string;
    participants: Participant[];
    last_message: LastMessage | null;
    unread_count: number;
}

interface Message {
    id: number;
    message: string;
    user_id: number;
    sender: Participant;
    created_at: string;
    is_mine: boolean;
}

interface WorkspaceUser {
    id: number;
    name: string;
    avatar: string | null;
}

interface Project {
    id: number;
    title: string;
}

interface Props {
    conversations: Conversation[];
    workspaceUsers: WorkspaceUser[];
    projects: Project[];
}

function getInitials(name: string) {
    return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
}

function formatTime(dateStr: string) {
    const d = new Date(dateStr);
    const now = new Date();
    const diffDays = Math.floor((now.getTime() - d.getTime()) / 86400000);
    if (diffDays === 0) return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    if (diffDays === 1) return 'Yesterday';
    return d.toLocaleDateString([], { month: 'short', day: 'numeric' });
}

export default function ChatIndex({ conversations: initialConversations, workspaceUsers, projects }: Props) {
    const { t } = useTranslation();
    const { auth } = usePage().props as any;
    const currentUser = auth?.user;

    const [conversations, setConversations] = useState<Conversation[]>(initialConversations);
    const [activeConversation, setActiveConversation] = useState<Conversation | null>(null);
    const [messages, setMessages] = useState<Message[]>([]);
    const [participantsRead, setParticipantsRead] = useState<Record<string, string | null>>({});
    const [messageInput, setMessageInput] = useState('');
    const [searchTerm, setSearchTerm] = useState('');
    const [sending, setSending] = useState(false);
    const [newChatOpen, setNewChatOpen] = useState(false);

    // New conversation form state
    const [newType, setNewType] = useState<'direct' | 'group' | 'project'>('direct');
    const [newName, setNewName] = useState('');
    const [selectedParticipants, setSelectedParticipants] = useState<number[]>([]);
    const [selectedProject, setSelectedProject] = useState<string>('');

    const messagesEndRef = useRef<HTMLDivElement>(null);
    const pollingRef = useRef<ReturnType<typeof setInterval> | null>(null);

    // Sync conversations state whenever Inertia props update (e.g. after new conversation created)
    useEffect(() => {
        setConversations((prev) => {
            // Merge: keep local unread counts, add any new conversations from server
            const merged = initialConversations.map((ic) => {
                const local = prev.find((p) => p.id === ic.id);
                return local ? { ...ic, unread_count: local.unread_count } : ic;
            });
            return merged;
        });

        // Auto-open conversation from URL param whenever initialConversations updates
        const params = new URLSearchParams(window.location.search);
        const convId = params.get('conversation');
        if (convId) {
            const found = initialConversations.find((c) => c.id === parseInt(convId));
            if (found) openConversation(found);
        }
    }, [initialConversations]);

    const fetchMessages = useCallback(async (conversationId: number) => {
        try {
            const res = await fetch(route('chat.messages', conversationId), {
                headers: { Accept: 'application/json' },
            });
            if (!res.ok) return;
            const data = await res.json();
            // Support both old (array) and new ({messages, participants_read}) shape
            if (Array.isArray(data)) {
                setMessages(data);
            } else {
                setMessages(data.messages ?? []);
                setParticipantsRead(data.participants_read ?? {});
            }
        } catch {
            // silent
        }
    }, []);

    const openConversation = useCallback(
        (conv: Conversation) => {
            setActiveConversation(conv);
            setMessages([]);
            setParticipantsRead({});
            fetchMessages(conv.id);

            // Clear polling on old conversation
            if (pollingRef.current) clearInterval(pollingRef.current);
            // Poll every 3 seconds
            pollingRef.current = setInterval(() => fetchMessages(conv.id), 3000);

            // Mark as read locally
            setConversations((prev) =>
                prev.map((c) => (c.id === conv.id ? { ...c, unread_count: 0 } : c))
            );
        },
        [fetchMessages]
    );

    // Cleanup polling on unmount
    useEffect(() => {
        return () => {
            if (pollingRef.current) clearInterval(pollingRef.current);
        };
    }, []);

    // Scroll to bottom when messages change
    useEffect(() => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    }, [messages]);

    const sendMessage = async () => {
        if (!messageInput.trim() || !activeConversation || sending) return;
        setSending(true);
        try {
            // Get the freshest CSRF token: prefer Inertia's shared token (updated on each navigation)
            // over the static meta tag (only set on initial page load)
            const csrfToken =
                (window as any).page?.props?.csrf_token ||
                (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content ||
                '';

            const res = await fetch(route('chat.send', activeConversation.id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ message: messageInput.trim() }),
            });
            if (!res.ok) {
                const errText = await res.text().catch(() => '');
                toast.error(`Failed to send message (${res.status})`);
                console.error('Chat send failed:', res.status, errText);
                return;
            }
            const msg: Message = await res.json();
            setMessages((prev) => [...prev, msg]);
            setMessageInput('');

            // Update last message in sidebar
            setConversations((prev) =>
                prev.map((c) =>
                    c.id === activeConversation.id
                        ? {
                              ...c,
                              last_message: {
                                  message: msg.message,
                                  sender_name: currentUser?.name ?? '',
                                  created_at: msg.created_at,
                              },
                          }
                        : c
                )
            );
        } finally {
            setSending(false);
        }
    };

    const handleKeyDown = (e: React.KeyboardEvent) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    };

    const createConversation = () => {
        if (selectedParticipants.length === 0) {
            toast.error('Select at least one participant');
            return;
        }

        router.post(
            route('chat.store'),
            {
                type: newType,
                name: newName || undefined,
                participant_ids: selectedParticipants,
                project_id: newType === 'project' && selectedProject ? parseInt(selectedProject) : undefined,
            },
            {
                onSuccess: () => {
                    setNewChatOpen(false);
                    setNewName('');
                    setSelectedParticipants([]);
                    setSelectedProject('');
                    setNewType('direct');
                },
                onError: () => toast.error('Failed to create conversation'),
            }
        );
    };

    const toggleParticipant = (id: number) => {
        if (newType === 'direct') {
            setSelectedParticipants([id]);
        } else {
            setSelectedParticipants((prev) =>
                prev.includes(id) ? prev.filter((p) => p !== id) : [...prev, id]
            );
        }
    };

    const filtered = conversations.filter((c) =>
        c.name.toLowerCase().includes(searchTerm.toLowerCase())
    );

    const typeIcon = (type: string) => {
        if (type === 'group') return <Users className="h-3.5 w-3.5" />;
        if (type === 'project') return <FolderOpen className="h-3.5 w-3.5" />;
        return <UserIcon className="h-3.5 w-3.5" />;
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Chat', href: route('chat.index') }]}>
            <Head title={t('Chat')} />
            <div className="flex h-[calc(100vh-4rem)] overflow-hidden">
                {/* Sidebar */}
                <div className="flex w-80 flex-shrink-0 flex-col border-r bg-background">
                    <div className="flex items-center justify-between border-b p-4">
                        <h2 className="text-lg font-semibold">{t('Chat')}</h2>
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button size="icon" variant="ghost" onClick={() => setNewChatOpen(true)}>
                                    <Plus className="h-4 w-4" />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>{t('New Conversation')}</TooltipContent>
                        </Tooltip>
                    </div>

                    {/* Search */}
                    <div className="p-3">
                        <div className="relative">
                            <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                            <Input
                                className="pl-9"
                                placeholder={t('Search conversations...')}
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                            />
                        </div>
                    </div>

                    {/* Conversation list */}
                    <ScrollArea className="flex-1">
                        {filtered.length === 0 ? (
                            <div className="p-6 text-center text-sm text-muted-foreground">
                                {t('No conversations yet')}
                            </div>
                        ) : (
                            filtered.map((conv) => (
                                <button
                                    key={conv.id}
                                    onClick={() => openConversation(conv)}
                                    className={cn(
                                        'flex w-full items-start gap-3 px-4 py-3 text-left transition-colors hover:bg-muted/50',
                                        activeConversation?.id === conv.id && 'bg-muted'
                                    )}
                                >
                                    <div className="relative mt-0.5 flex-shrink-0">
                                        {conv.type === 'direct' ? (
                                            <Avatar className="h-10 w-10">
                                                <AvatarImage src={conv.participants.find((p) => p.id !== currentUser?.id)?.avatar ?? undefined} />
                                                <AvatarFallback>{getInitials(conv.name)}</AvatarFallback>
                                            </Avatar>
                                        ) : (
                                            <div className="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                                                {conv.type === 'group' ? <Hash className="h-5 w-5" /> : <FolderOpen className="h-5 w-5" />}
                                            </div>
                                        )}
                                        {conv.unread_count > 0 && (
                                            <Badge className="absolute -right-1 -top-1 h-5 min-w-[1.25rem] justify-center rounded-full px-1 text-[10px]">
                                                {conv.unread_count}
                                            </Badge>
                                        )}
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <div className="flex items-center justify-between gap-2">
                                            <span className="truncate text-sm font-medium">{conv.name}</span>
                                            {conv.last_message && (
                                                <span className="flex-shrink-0 text-[11px] text-muted-foreground">
                                                    {formatTime(conv.last_message.created_at)}
                                                </span>
                                            )}
                                        </div>
                                        {conv.last_message ? (
                                            <p className="truncate text-xs text-muted-foreground">
                                                {conv.last_message.sender_name}: {conv.last_message.message}
                                            </p>
                                        ) : (
                                            <p className="text-xs text-muted-foreground italic">{t('No messages yet')}</p>
                                        )}
                                    </div>
                                </button>
                            ))
                        )}
                    </ScrollArea>
                </div>

                {/* Chat area */}
                <div className="flex flex-1 flex-col">
                    {activeConversation ? (
                        <>
                            {/* Header */}
                            <div className="flex items-center gap-3 border-b px-6 py-3">
                                {activeConversation.type === 'direct' ? (
                                    <Avatar className="h-9 w-9">
                                        <AvatarImage src={activeConversation.participants.find((p) => p.id !== currentUser?.id)?.avatar ?? undefined} />
                                        <AvatarFallback>{getInitials(activeConversation.name)}</AvatarFallback>
                                    </Avatar>
                                ) : (
                                    <div className="flex h-9 w-9 items-center justify-center rounded-full bg-primary/10 text-primary">
                                        {activeConversation.type === 'group' ? <Hash className="h-5 w-5" /> : <FolderOpen className="h-5 w-5" />}
                                    </div>
                                )}
                                <div>
                                    <p className="font-medium">{activeConversation.name}</p>
                                    <p className="text-xs text-muted-foreground">
                                        {activeConversation.participants.length} {t('members')}
                                    </p>
                                </div>
                            </div>

                            {/* Messages */}
                            <ScrollArea className="flex-1 px-6 py-4">
                                <div className="space-y-4">
                                    {messages.map((msg) => {
                                        // Compute seen: any other participant read after this message was sent
                                        const msgTime = new Date(msg.created_at).getTime();
                                        const isSeen = msg.is_mine && Object.values(participantsRead).some(
                                            (t) => t && new Date(t).getTime() >= msgTime
                                        );

                                        return (
                                        <div
                                            key={msg.id}
                                            className={cn('flex gap-2', msg.is_mine && 'flex-row-reverse')}
                                        >
                                            {!msg.is_mine && (
                                                <Avatar className="mt-1 h-8 w-8 flex-shrink-0">
                                                    <AvatarImage src={msg.sender.avatar ?? undefined} />
                                                    <AvatarFallback className="text-xs">{getInitials(msg.sender.name)}</AvatarFallback>
                                                </Avatar>
                                            )}
                                            <div className={cn('flex max-w-[70%] flex-col gap-1', msg.is_mine && 'items-end')}>
                                                {!msg.is_mine && (
                                                    <span className="text-xs font-medium text-muted-foreground">{msg.sender.name}</span>
                                                )}
                                                <div
                                                    className={cn(
                                                        'rounded-2xl px-4 py-2 text-sm',
                                                        msg.is_mine
                                                            ? 'rounded-tr-sm bg-primary text-primary-foreground'
                                                            : 'rounded-tl-sm bg-muted'
                                                    )}
                                                >
                                                    {msg.message}
                                                </div>
                                                {/* Timestamp + read receipt */}
                                                <div className={cn('flex items-center gap-1', msg.is_mine && 'flex-row-reverse')}>
                                                    <span className="text-[11px] text-muted-foreground">{formatTime(msg.created_at)}</span>
                                                    {msg.is_mine && (
                                                        isSeen
                                                            ? <CheckCheck className="h-3.5 w-3.5 text-blue-500" title="Seen" />
                                                            : <Check className="h-3.5 w-3.5 text-muted-foreground" title="Sent" />
                                                    )}
                                                </div>
                                            </div>
                                        </div>
                                        );
                                    })}
                                    <div ref={messagesEndRef} />
                                </div>
                            </ScrollArea>

                            {/* Input */}
                            <div className="border-t px-6 py-4">
                                <div className="flex gap-2">
                                    <Input
                                        placeholder={t('Type a message...')}
                                        value={messageInput}
                                        onChange={(e) => setMessageInput(e.target.value)}
                                        onKeyDown={handleKeyDown}
                                        className="flex-1"
                                    />
                                    <Button onClick={sendMessage} disabled={!messageInput.trim() || sending} size="icon">
                                        <Send className="h-4 w-4" />
                                    </Button>
                                </div>
                                <p className="mt-1 text-[11px] text-muted-foreground">{t('Press Enter to send')}</p>
                            </div>
                        </>
                    ) : (
                        <div className="flex flex-1 flex-col items-center justify-center gap-4 text-center">
                            <div className="flex h-20 w-20 items-center justify-center rounded-full bg-muted">
                                <MessageSquare className="h-10 w-10 text-muted-foreground" />
                            </div>
                            <div>
                                <p className="text-lg font-medium">{t('Your messages')}</p>
                                <p className="text-sm text-muted-foreground">{t('Select a conversation or start a new one')}</p>
                            </div>
                            <Button onClick={() => setNewChatOpen(true)}>
                                <Plus className="mr-2 h-4 w-4" />
                                {t('New Conversation')}
                            </Button>
                        </div>
                    )}
                </div>
            </div>

            {/* New Conversation Dialog */}
            <Dialog open={newChatOpen} onOpenChange={setNewChatOpen}>
                <DialogContent className="max-w-md">
                    <DialogHeader>
                        <DialogTitle>{t('New Conversation')}</DialogTitle>
                    </DialogHeader>
                    <div className="space-y-4 py-2">
                        {/* Type */}
                        <div className="space-y-1.5">
                            <Label>{t('Type')}</Label>
                            <div className="flex gap-2">
                                {(['direct', 'group', 'project'] as const).map((type) => (
                                    <Button
                                        key={type}
                                        variant={newType === type ? 'default' : 'outline'}
                                        size="sm"
                                        onClick={() => {
                                            setNewType(type);
                                            setSelectedParticipants([]);
                                        }}
                                        className="flex-1 capitalize"
                                    >
                                        {t(type.charAt(0).toUpperCase() + type.slice(1))}
                                    </Button>
                                ))}
                            </div>
                        </div>

                        {/* Name (for group/project) */}
                        {(newType === 'group') && (
                            <div className="space-y-1.5">
                                <Label>{t('Group Name')}</Label>
                                <Input
                                    placeholder={t('e.g. Design Team')}
                                    value={newName}
                                    onChange={(e) => setNewName(e.target.value)}
                                />
                            </div>
                        )}

                        {/* Project selector */}
                        {newType === 'project' && (
                            <div className="space-y-1.5">
                                <Label>{t('Project')}</Label>
                                <Select value={selectedProject} onValueChange={setSelectedProject}>
                                    <SelectTrigger>
                                        <SelectValue placeholder={t('Select a project')} />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {projects.map((p) => (
                                            <SelectItem key={p.id} value={String(p.id)}>
                                                {p.title}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                        )}

                        {/* Participants */}
                        <div className="space-y-1.5">
                            <Label>
                                {newType === 'direct' ? t('Select User') : t('Add Participants')}
                            </Label>
                            <ScrollArea className="h-48 rounded-md border p-2">
                                <div className="space-y-1">
                                    {workspaceUsers.map((u) => (
                                        <button
                                            key={u.id}
                                            onClick={() => toggleParticipant(u.id)}
                                            className={cn(
                                                'flex w-full items-center gap-3 rounded-md px-2 py-2 text-left text-sm transition-colors hover:bg-muted',
                                                selectedParticipants.includes(u.id) && 'bg-primary/10 font-medium text-primary'
                                            )}
                                        >
                                            <Avatar className="h-7 w-7">
                                                <AvatarImage src={u.avatar ?? undefined} />
                                                <AvatarFallback className="text-xs">{getInitials(u.name)}</AvatarFallback>
                                            </Avatar>
                                            {u.name}
                                            {selectedParticipants.includes(u.id) && (
                                                <Badge className="ml-auto text-[10px]">{t('Selected')}</Badge>
                                            )}
                                        </button>
                                    ))}
                                </div>
                            </ScrollArea>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button variant="outline" onClick={() => setNewChatOpen(false)}>
                            {t('Cancel')}
                        </Button>
                        <Button onClick={createConversation} disabled={selectedParticipants.length === 0}>
                            {t('Start Chat')}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </AppLayout>
    );
}
