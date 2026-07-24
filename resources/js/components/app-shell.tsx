import { SidebarProvider } from '@/components/ui/sidebar';
import { useLayout } from '@/contexts/LayoutContext';
import { FloatingChatGpt } from '@/components/FloatingChatGpt';
import CookieConsentBanner from '@/components/privacy-notice';
import { CommandPalette } from '@/components/CommandPalette';
import { cn } from '@/lib/utils';
import { useState } from 'react';

interface AppShellProps {
    children: React.ReactNode;
    variant?: 'header' | 'sidebar';
}

export function AppShell({ children, variant = 'header' }: AppShellProps) {
    const [isOpen, setIsOpen] = useState(() => (typeof window !== 'undefined' ? localStorage.getItem('sidebar') !== 'false' : true));

    const handleSidebarChange = (open: boolean) => {
        setIsOpen(open);

        if (typeof window !== 'undefined') {
            localStorage.setItem('sidebar', String(open));
        }
    };

    if (variant === 'header') {
        return (
            <div className="flex min-h-screen w-full flex-col">
                {children}
                <CommandPalette />
                <FloatingChatGpt />
                <CookieConsentBanner />
            </div>
        );
    }

    const { position, isRtl } = useLayout();
    
    // Check if current language is RTL
    const isLanguageRTL = typeof window !== 'undefined' && ['ar', 'he'].includes(document.documentElement.lang || 'en');
    
    // For RTL languages, always use right position
    const effectivePosition = isLanguageRTL ? 'right' : position;

    return (
        <SidebarProvider defaultOpen={isOpen} open={isOpen} onOpenChange={handleSidebarChange}>
            <div className="flex w-full">
                {children}
                <CommandPalette />
                <FloatingChatGpt />
                <CookieConsentBanner />
            </div>
        </SidebarProvider>
    );
}
