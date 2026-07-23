import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useLayout } from '@/contexts/LayoutContext';
import { useSidebarSettings } from '@/contexts/SidebarContext';
import { useBrand } from '@/contexts/BrandContext';

import { type NavItem } from '@/types';
import { Link, usePage, router } from '@inertiajs/react';
import { BookOpen, Contact, Folder, LayoutGrid, ShoppingBag, Users, Tag, FileIcon, Settings, BarChart, Barcode, FileText, Briefcase, CheckSquare, Calendar, CreditCard, Nfc, Ticket, Gift, DollarSign, MessageSquare, CalendarDays, Palette, Image, Mail, Mail as VCard, ChevronDown, Building2, Globe, FolderOpen, FolderKanban, ClipboardList, Zap, Clock, Bug, Receipt, TrendingUp, Bot, Video, Bell, HelpCircle, Workflow, Activity, Archive, ListTodo } from 'lucide-react';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import AppLogo from './app-logo';
import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { hasPermission } from '@/utils/authorization';



export function AppSidebar() {
    const { t, i18n } = useTranslation();
    const { auth, isSaasMode, globalSettings } = usePage().props as any;
    const permissions = auth?.permissions || [];
    
    // Check if current language is RTL
    const isRTL = ['ar', 'he'].includes(i18n.language);

    const isSuperAdmin = auth?.user?.type === 'superadmin';

    // ─── SaaS Super Admin ────────────────────────────────────────────────────
    const getSuperAdminNavItems = (): NavItem[] => {
        const items: NavItem[] = [];
        if (hasPermission(permissions, 'dashboard_view')) {
            items.push({ title: t('Dashboard'), href: route('dashboard'), icon: LayoutGrid, group: t('Overview') });
        }
        if (hasPermission(permissions, 'company_view_any')) {
            items.push({ title: t('Companies'), href: route('companies.index'), icon: Building2, group: t('Management') });
        }
        if (hasPermission(permissions, 'media_view_any')) {
            items.push({ title: t('Media Library'), href: route('media-library'), icon: Image, group: t('Management') });
        }
        if (hasPermission(permissions, 'plan_view_any') || hasPermission(permissions, 'plan_manage_requests') || hasPermission(permissions, 'plan_manage_orders')) {
            const planChildren = [];
            if (hasPermission(permissions, 'plan_view_any')) planChildren.push({ title: t('Plan'), href: route('plans.index') });
            if (hasPermission(permissions, 'plan_manage_requests')) planChildren.push({ title: t('Plan Requests'), href: route('plan-requests.index') });
            if (hasPermission(permissions, 'plan_manage_orders')) planChildren.push({ title: t('Plan Orders'), href: route('plan-orders.index') });
            items.push({ title: t('Plans'), icon: CreditCard, group: t('Management'), children: planChildren });
        }
        if (hasPermission(permissions, 'coupon_view_any')) {
            items.push({ title: t('Coupons'), href: route('coupons.index'), icon: Ticket, group: t('Management') });
        }
        if (hasPermission(permissions, 'currency_view_any')) {
            items.push({ title: t('Currency'), href: route('currencies.index'), icon: DollarSign, group: t('Management') });
        }
        if (hasPermission(permissions, 'referral_view_any')) {
            items.push({ title: t('Referral Program'), href: route('referral.index'), icon: Gift, group: t('Management') });
        }
        const landingChildren = [];
        if (hasPermission(permissions, 'landing_page_manage')) landingChildren.push({ title: t('Landing Page'), href: route('landing-page') });
        if (hasPermission(permissions, 'custom_page_view_any')) landingChildren.push({ title: t('Custom Pages'), href: route('landing-page.custom-pages.index') });
        if (hasPermission(permissions, 'contact_view_any')) landingChildren.push({ title: t('Contact Inquiries'), href: route('contacts.index') });
        if (hasPermission(permissions, 'newsletter_view_any')) landingChildren.push({ title: t('Newsletter'), href: route('newsletters.index') });
        if (landingChildren.length > 0) {
            items.push({ title: t('Landing Page'), icon: Globe, group: t('Management'), children: landingChildren });
        }
        if (hasPermission(permissions, 'settings_view')) {
            items.push({ title: t('Settings'), href: route('settings'), icon: Settings, group: t('System Control') });
        }
        return items;
    };

    // ─── Common items (all non-superadmin users) ──────────────────────────────
    const buildCommonNavItems = (): NavItem[] => {
        const items: NavItem[] = [];

        // Overview
        if (hasPermission(permissions, 'dashboard_view')) {
            items.push({ title: t('Dashboard'), href: route('dashboard'), icon: LayoutGrid, group: t('Overview') });
        }
        if (hasPermission(permissions, 'task_calendar_view')) {
            try { items.push({ title: t('Calendar'), href: route('task-calendar.index'), icon: Calendar, group: t('Overview') }); } catch {}
        }

        // Project Management
        if (hasPermission(permissions, 'workspace_view_any')) {
            items.push({ title: t('Workspaces'), href: route('workspaces.index'), icon: Building2, group: t('Project Management') });
        }
        if (hasPermission(permissions, 'project_view_any')) {
            items.push({ title: t('Portfolios'), href: route('portfolios.index'), icon: FolderKanban, group: t('Project Management') });
            items.push({ title: t('Projects'), href: route('projects.index'), icon: FolderOpen, group: t('Project Management') });
        }
        items.push({ title: t('Forms'), href: route('forms.index'), icon: ClipboardList, group: t('Project Management') });
        if (hasPermission(permissions, 'task_view_any')) {
            const taskChildren = [{ title: t('All Tasks'), href: route('tasks.index') }];
            if (hasPermission(permissions, 'task_manage_stages')) taskChildren.push({ title: t('Task Stages'), href: route('task-stages.index') });
            items.push({ title: t('Tasks'), icon: CheckSquare, group: t('Project Management'), children: taskChildren });
        }
        if (hasPermission(permissions, 'bug_view_any')) {
            const bugChildren = [{ title: t('All Bugs'), href: route('bugs.index') }];
            if (hasPermission(permissions, 'bug_manage_statuses')) bugChildren.push({ title: t('Bug Statuses'), href: route('bug-statuses.index') });
            items.push({ title: t('Bugs'), icon: Bug, group: t('Project Management'), children: bugChildren });
        }
        if (hasPermission(permissions, 'todo_view_any')) {
            try { items.push({ title: t('ToDos'), href: route('todos.index'), icon: ListTodo, group: t('Project Management') }); } catch {}
        }
        if (hasPermission(permissions, 'project_report_view_any')) {
            try { items.push({ title: t('Project Reports'), href: route('project-reports.index'), icon: TrendingUp, group: t('Project Management') }); } catch {}
        }

        // Time Tracking
        if (hasPermission(permissions, 'timesheet_view_any')) {
            const timesheetChildren = [
                { title: t('My Timesheets'), href: route('timesheets.index') },
                { title: t('Calendar View'), href: route('timesheets.calendar-view') },
                { title: t('Daily View'), href: route('timesheets.daily-view') },
                { title: t('Weekly View'), href: route('timesheets.weekly-view') },
                { title: t('Monthly View'), href: route('timesheets.monthly-view') },
            ];
            if (hasPermission(permissions, 'timesheet_approve')) timesheetChildren.push({ title: t('Approvals'), href: route('timesheet-approvals.index') });
            if (hasPermission(permissions, 'timesheet_generate_reports')) timesheetChildren.push({ title: t('Reports'), href: route('timesheet-reports.index') });
            items.push({ title: t('Timesheets'), icon: Clock, group: t('Time Tracking'), children: timesheetChildren });
        }

        // Finance
        if (hasPermission(permissions, 'invoice_view_any')) {
            items.push({ title: t('Invoices'), href: route('invoices.index'), icon: FileText, group: t('Finance') });
        }
        if (hasPermission(permissions, 'budget_view_any') || hasPermission(permissions, 'expense_view_any')) {
            const budgetChildren = [];
            if (hasPermission(permissions, 'budget_dashboard_view')) budgetChildren.push({ title: t('Budget Dashboard'), href: route('budgets.dashboard') });
            if (hasPermission(permissions, 'budget_view_any')) budgetChildren.push({ title: t('Budgets'), href: route('budgets.index') });
            if (hasPermission(permissions, 'expense_view_any')) budgetChildren.push({ title: t('Expenses'), href: route('expenses.index') });
            if (hasPermission(permissions, 'expense_approval_approve')) budgetChildren.push({ title: t('Expense Approvals'), href: route('expense-approvals.index') });
            items.push({ title: t('Budget & Expenses'), icon: Receipt, group: t('Finance'), children: budgetChildren.length > 0 ? budgetChildren : undefined });
        }

        // Communications & Content
        if (hasPermission(permissions, 'note_view_any')) {
            try { items.push({ title: t('Notes'), href: route('notes.index'), icon: FileIcon, group: t('Communications & Content') }); } catch {}
        }
        if (hasPermission(permissions, 'zoom_meeting_view_any')) {
            try { items.push({ title: t('Zoom Meetings'), href: route('zoom-meetings.index'), icon: Video, group: t('Communications & Content') }); } catch {}
        }
        if (hasPermission(permissions, 'google_meeting_view_any')) {
            try { items.push({ title: t('Google Meetings'), href: route('google-meetings.index'), icon: Video, group: t('Communications & Content') }); } catch {}
        }
        if (hasPermission(permissions, 'contract_view_any') || hasPermission(permissions, 'contract_type_view_any')) {
            const contractChildren = [];
            if (hasPermission(permissions, 'contract_view_any')) try { contractChildren.push({ title: t('Contracts'), href: route('contracts.index') }); } catch {}
            if (hasPermission(permissions, 'contract_type_view_any')) try { contractChildren.push({ title: t('Contract Types'), href: route('contract-types.index') }); } catch {}
            if (contractChildren.length > 0) {
                items.push({
                    title: t('Contracts'), icon: FileText, group: t('Communications & Content'),
                    children: contractChildren.length > 1 ? contractChildren : undefined,
                    href: contractChildren.length === 1 ? contractChildren[0].href : undefined,
                });
            }
        }

        // Chat (custom) — always show when authenticated
        items.push({ title: t('Chat'), href: route('chat.index'), icon: MessageSquare, group: t('Communications & Content') });

        // Integrations (custom)
        const integrationChildren: { title: string; href: string }[] = [
            { title: t('Knowledge Base'), href: route('kb.index') },
            { title: t('Agents'), href: route('agents.index') },
            { title: t('BYOA (API Keys)'), href: route('api-keys.index') },
            { title: t('Zapier'), href: route('zapier.index') },
        ];
        items.push({ title: t('Integrations'), icon: Zap, group: t('Communications & Content'), children: integrationChildren });

        if (hasPermission(permissions, 'media_view_any')) {
            items.push({ title: t('Media Library'), href: route('media-library'), icon: Image, group: t('Communications & Content') });
        }

        return items;
    };

    // ─── SaaS Company User ───────────────────────────────────────────────────
    const getSaasNavItems = (): NavItem[] => {
        const items = buildCommonNavItems();
        const planChildren = [];
        if (hasPermission(permissions, 'plan_view_any')) planChildren.push({ title: t('Plans'), href: route('plans.index') });
        if (hasPermission(permissions, 'plan_view_my_requests')) planChildren.push({ title: t('My Plan Requests'), href: route('my-plan-requests.index') });
        if (hasPermission(permissions, 'plan_view_my_orders')) planChildren.push({ title: t('My Plan Orders'), href: route('my-plan-orders.index') });
        if (planChildren.length > 0) {
            items.push({ title: t('Plans'), icon: CreditCard, group: t('Subscription & Billing'), children: planChildren.length > 1 ? planChildren : undefined, href: planChildren.length === 1 ? planChildren[0].href : undefined });
        }
        if (hasPermission(permissions, 'referral_view_any')) {
            items.push({ title: t('Referral Program'), href: route('referral.index'), icon: Gift, group: t('Growth') });
        }
        if (hasPermission(permissions, 'notification_template_view_any')) {
            items.push({ title: t('Notification Templates'), href: route('notification-templates.index'), icon: Bell, group: t('System Control') });
        }
        if (hasPermission(permissions, 'settings_view')) {
            items.push({ title: t('Settings'), href: route('settings'), icon: Settings, group: t('System Control') });
        }
        return items;
    };

    // ─── Non-SaaS ────────────────────────────────────────────────────────────
    const getNonSaasNavItems = (): NavItem[] => {
        const items = buildCommonNavItems();
        const landingChildren = [];
        if (hasPermission(permissions, 'landing_page_manage')) landingChildren.push({ title: t('Landing Page'), href: route('landing-page') });
        if (hasPermission(permissions, 'custom_page_view_any')) landingChildren.push({ title: t('Custom Pages'), href: route('landing-page.custom-pages.index') });
        if (hasPermission(permissions, 'newsletter_view_any')) landingChildren.push({ title: t('Newsletters'), href: route('newsletters.index') });
        if (hasPermission(permissions, 'contact_view_any')) landingChildren.push({ title: t('Contact Inquiries'), href: route('contacts.index') });
        if (landingChildren.length > 0) {
            items.push({ title: t('Landing Page'), icon: Globe, group: t('Website & Marketing'), children: landingChildren });
        }
        if (hasPermission(permissions, 'currency_view_any')) {
            items.push({ title: t('Currency'), href: route('currencies.index'), icon: DollarSign, group: t('System Control') });
        }
        if (hasPermission(permissions, 'coupon_view_any')) {
            items.push({ title: t('Coupons'), href: route('coupons.index'), icon: Ticket, group: t('System Control') });
        }
        if (hasPermission(permissions, 'email_template_view_any')) {
            items.push({ title: t('Email Templates'), href: route('email-templates.index'), icon: Mail, group: t('System Control') });
        }
        if (hasPermission(permissions, 'notification_template_view_any')) {
            items.push({ title: t('Notification Templates'), href: route('notification-templates.index'), icon: Bell, group: t('System Control') });
        }
        if (hasPermission(permissions, 'settings_view')) {
            items.push({ title: t('Settings'), href: route('settings'), icon: Settings, group: t('System Control') });
        }
        return items;
    };

    const getNavItems = (): NavItem[] => {
        if (isSaasMode && isSuperAdmin) return getSuperAdminNavItems();
        if (isSaasMode) return getSaasNavItems();
        return getNonSaasNavItems();
    };

    const mainNavItems = getNavItems();

    const { position, effectivePosition, isRtl } = useLayout();
    const { variant, collapsible, style } = useSidebarSettings();
    const { logoLight, logoDark, favicon, updateBrandSettings } = useBrand();
    const [sidebarStyle, setSidebarStyle] = useState({});

    useEffect(() => {

        // Apply styles based on sidebar style
        if (style === 'colored') {
            setSidebarStyle({ backgroundColor: 'var(--primary)', color: 'white' });
        } else if (style === 'gradient') {
            setSidebarStyle({
                background: 'linear-gradient(to bottom, var(--primary), color-mix(in srgb, var(--primary), transparent 20%))',
                color: 'white'
            });
        } else {
            setSidebarStyle({});
        }
    }, [style]);

    const filteredNavItems = mainNavItems;

    // Get the first available menu item's href for logo link
    const getFirstAvailableHref = () => {
        if (filteredNavItems.length === 0) return route('dashboard');

        const firstItem = filteredNavItems[0];
        if (firstItem.href) {
            return firstItem.href;
        } else if (firstItem.children && firstItem.children.length > 0) {
            return firstItem.children[0].href || route('dashboard');
        }
        return route('dashboard');
    };

    return (
        <Sidebar
            key={`sidebar-${effectivePosition}-${isRtl}`}
            side={effectivePosition}
            collapsible={collapsible}
            variant={variant}
            className={style !== 'plain' ? 'sidebar-custom-style' : ''}
        >
            <SidebarHeader className={style !== 'plain' ? 'sidebar-styled' : ''} style={sidebarStyle}>
                <div className="flex justify-center items-center p-2">
                    <Link href={getFirstAvailableHref()} prefetch className="flex items-center justify-center">
                        {/* Logo for expanded sidebar */}
                        <div className="h-8 group-data-[collapsible=icon]:hidden flex items-center">
                            {(() => {
                                const isDark = document.documentElement.classList.contains('dark');
                                const currentLogo = isDark ? logoLight : logoDark;
                                const displayUrl = currentLogo ? (
                                    currentLogo.startsWith('http') ? currentLogo :
                                        currentLogo.startsWith('/storage/') ? `${window.location.origin}${currentLogo}` :
                                            currentLogo.startsWith('/') ? `${window.location.origin}${currentLogo}` : currentLogo
                                ) : '';

                                return displayUrl ? (
                                    <img
                                        key={`${currentLogo}-${Date.now()}`}
                                        src={displayUrl}
                                        alt="Logo"
                                        className="h-8 w-auto max-w-[120px] transition-all duration-200"
                                        onError={() => updateBrandSettings({ [isDark ? 'logoLight' : 'logoDark']: '' })}
                                    />
                                ) : (
                                    <div className="h-8 text-inherit font-semibold flex items-center text-lg tracking-tight">
                                        WorkDo
                                    </div>
                                );
                            })()}
                        </div>

                        {/* Icon for collapsed sidebar */}
                        <div className="h-8 w-8 hidden group-data-[collapsible=icon]:block">
                            {(() => {
                                const displayFavicon = favicon ? (
                                    favicon.startsWith('http') ? favicon :
                                        favicon.startsWith('/storage/') ? `${window.location.origin}${favicon}` :
                                            favicon.startsWith('/') ? `${window.location.origin}${favicon}` : favicon
                                ) : '';

                                return displayFavicon ? (
                                    <img
                                        key={`${favicon}-${Date.now()}`}
                                        src={displayFavicon}
                                        alt="Icon"
                                        className="h-8 w-8 transition-all duration-200"
                                        onError={() => updateBrandSettings({ favicon: '' })}
                                    />
                                ) : (
                                    <div className="h-8 w-8 bg-primary text-white rounded flex items-center justify-center font-bold shadow-sm">
                                        W
                                    </div>
                                );
                            })()}
                        </div>
                    </Link>
                </div>


            </SidebarHeader>

            <SidebarContent style={sidebarStyle} className={style !== 'plain' ? 'sidebar-styled' : ''}>
                <NavMain items={filteredNavItems} position={effectivePosition} sidebarStyle={style} />
            </SidebarContent>

            <SidebarFooter>
                {/* <NavFooter items={footerNavItems} className="mt-auto" position={position} /> */}
                {/* Profile menu moved to header */}
            </SidebarFooter>
        </Sidebar>
    );
}