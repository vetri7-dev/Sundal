import { useState, useEffect } from 'react';
import { router } from '@inertiajs/react';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';
import { X, Plus } from 'lucide-react';
import { ZoomMeeting, Project, User } from '@/types';

interface Props {
    isOpen: boolean;
    onClose: () => void;
    meeting?: ZoomMeeting;
    projects: Project[];
    members: User[];
}

export default function ZoomMeetingModal({ isOpen, onClose, meeting, projects, members }: Props) {
    const [formData, setFormData] = useState({
        title: '',
        description: '',
        start_time: '',
        duration: 60,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        project_id: '',
        password: '',
        member_ids: [] as string[]
    });
    
    const [projectMembers, setProjectMembers] = useState<User[]>([]);
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [isSubmitting, setIsSubmitting] = useState(false);

    useEffect(() => {
        if (isOpen) {
            if (meeting) {
                setFormData({
                    title: meeting.title || '',
                    description: meeting.description || '',
                    start_time: meeting.start_time ? new Date(meeting.start_time).toISOString().slice(0, 16) : '',
                    duration: meeting.duration || 60,
                    timezone: meeting.timezone || Intl.DateTimeFormat().resolvedOptions().timeZone,
                    project_id: meeting.project_id?.toString() || 'none',
                    password: meeting.password || '',
                    member_ids: meeting.members?.map(m => m.id.toString()) || []
                });
                if (meeting.project_id) {
                    loadProjectMembers(meeting.project_id.toString());
                }
            } else {
                setFormData({
                    title: '',
                    description: '',
                    start_time: '',
                    duration: 60,
                    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                    project_id: 'none',
                    password: '',
                    member_ids: []
                });
                setProjectMembers([]);
            }
            setErrors({});
            setIsSubmitting(false);
        }
    }, [isOpen, meeting]);

    const loadProjectMembers = async (projectId: string) => {
        if (!projectId || projectId === 'none') {
            setProjectMembers([]);
            return;
        }
        
        try {
            const response = await fetch(route('api.projects.members', projectId));
            if (response.ok) {
                const data = await response.json();
                setProjectMembers(data || []);
            } else {
                console.error('Failed to load project members:', response.status);
                setProjectMembers([]);
            }
        } catch (error) {
            console.error('Failed to load project members:', error);
            setProjectMembers([]);
        }
    };

    const handleProjectChange = (projectId: string) => {
        const actualProjectId = projectId === 'none' ? '' : projectId;
        handleChange('project_id', actualProjectId);
        handleChange('member_ids', []); // Reset selected members
        loadProjectMembers(actualProjectId);
    };

    const handleChange = (name: string, value: any) => {
        setFormData(prev => ({ ...prev, [name]: value }));
        if (errors[name]) {
            setErrors(prev => {
                const newErrors = { ...prev };
                delete newErrors[name];
                return newErrors;
            });
        }
    };



    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        
        if (isSubmitting) return;
        
        const newErrors: Record<string, string> = {};
        if (!formData.title) newErrors.title = 'Title is required';
        if (!formData.start_time) newErrors.start_time = 'Start time is required';
        
        if (Object.keys(newErrors).length > 0) {
            setErrors(newErrors);
            return;
        }

        setIsSubmitting(true);

        if (meeting) {
            router.put(route('zoom-meetings.update', meeting.id), formData, {
                onSuccess: () => {
                    setIsSubmitting(false);
                    onClose();
                },
                onError: (errors) => {
                    setIsSubmitting(false);
                    setErrors(errors);
                }
            });
        } else {
            router.post(route('zoom-meetings.store'), formData, {
                onSuccess: () => {
                    setIsSubmitting(false);
                    onClose();
                },
                onError: (errors) => {
                    setIsSubmitting(false);
                    setErrors(errors);
                }
            });
        }
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
                <DialogHeader>
                    <DialogTitle>
                        {meeting ? 'Edit Zoom Meeting' : 'Create Zoom Meeting'}
                    </DialogTitle>
                </DialogHeader>

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div>
                        <Label htmlFor="title">Meeting Title *</Label>
                        <Input
                            id="title"
                            value={formData.title}
                            onChange={(e) => handleChange('title', e.target.value)}
                            placeholder="Enter meeting title"
                            required
                        />
                        {errors.title && <p className="text-sm text-red-600">{errors.title}</p>}
                    </div>

                    <div>
                        <Label htmlFor="description">Description</Label>
                        <Textarea
                            id="description"
                            value={formData.description}
                            onChange={(e) => handleChange('description', e.target.value)}
                            placeholder="Meeting agenda or description"
                            rows={3}
                        />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <Label htmlFor="start_time">Start Date & Time *</Label>
                            <Input
                                id="start_time"
                                type="datetime-local"
                                value={formData.start_time}
                                onChange={(e) => handleChange('start_time', e.target.value)}
                                required
                            />
                            {errors.start_time && <p className="text-sm text-red-600">{errors.start_time}</p>}
                        </div>

                        <div>
                            <Label htmlFor="duration">Duration (minutes) *</Label>
                            <Input
                                id="duration"
                                type="number"
                                min="15"
                                max="480"
                                value={formData.duration}
                                onChange={(e) => handleChange('duration', parseInt(e.target.value) || 60)}
                                placeholder="Enter duration in minutes"
                                required
                            />
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <Label htmlFor="project_id">Project</Label>
                            <Select value={formData.project_id || 'none'} onValueChange={handleProjectChange}>
                                <SelectTrigger className="bg-white">
                                    <SelectValue placeholder="Select project (optional)" />
                                </SelectTrigger>
                                <SelectContent className="bg-white border shadow-lg z-[9999]">
                                    <SelectItem value="none" className="bg-white hover:bg-gray-100">No project</SelectItem>
                                    {projects.map((project) => (
                                        <SelectItem key={project.id} value={project.id.toString()} className="bg-white hover:bg-gray-100">
                                            {project.title}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>

                        <div>
                            <Label htmlFor="password">Password</Label>
                            <Input
                                id="password"
                                type="password"
                                value={formData.password}
                                onChange={(e) => handleChange('password', e.target.value)}
                                placeholder="Enter meeting password (optional)"
                            />
                        </div>
                    </div>

                    {formData.project_id && formData.project_id !== 'none' && (
                        <div>
                            <Label htmlFor="member_ids">Members</Label>
                            <Select 
                                value="" 
                                onValueChange={(value) => {
                                    if (value && !formData.member_ids.includes(value)) {
                                        handleChange('member_ids', [...formData.member_ids, value]);
                                    }
                                }}
                            >
                                <SelectTrigger className="bg-white">
                                    <SelectValue placeholder="Select members..." />
                                </SelectTrigger>
                                <SelectContent className="bg-white border shadow-lg z-[9999]">
                                    {projectMembers.map((member) => (
                                        <SelectItem 
                                            key={member.id} 
                                            value={member.id.toString()} 
                                            className="bg-white hover:bg-gray-100"
                                            disabled={formData.member_ids.includes(member.id.toString())}
                                        >
                                            {member.name} ({member.email}) - {member.role}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            
                            {formData.member_ids.length > 0 && (
                                <div className="mt-2 flex flex-wrap gap-2">
                                    {formData.member_ids.map((memberId) => {
                                        const member = projectMembers.find(m => m.id.toString() === memberId);
                                        return member ? (
                                            <Badge key={memberId} variant="secondary" className="flex items-center space-x-1">
                                                <span>{member.name}</span>
                                                <button
                                                    type="button"
                                                    onClick={() => {
                                                        handleChange('member_ids', formData.member_ids.filter(id => id !== memberId));
                                                    }}
                                                    className="ml-1 hover:text-red-600"
                                                >
                                                    <X className="h-3 w-3" />
                                                </button>
                                            </Badge>
                                        ) : null;
                                    })}
                                </div>
                            )}
                        </div>
                    )}


                    <DialogFooter>
                        <Button type="button" variant="outline" onClick={onClose}>
                            Cancel
                        </Button>
                        <Button type="submit" disabled={isSubmitting}>
                            {isSubmitting ? 'Saving...' : (meeting ? 'Update Meeting' : 'Create Meeting')}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}