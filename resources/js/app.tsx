// RTL is now handled by app.blade.php and LayoutContext

import '../css/app.css';
import '../css/dark-mode.css';

import { createInertiaApp, router } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { lazy, Suspense } from 'react';
import { LayoutProvider } from './contexts/LayoutContext';
import { SidebarProvider } from './contexts/SidebarContext';
import { BrandProvider } from './contexts/BrandContext';
import { ModalStackProvider } from './contexts/ModalStackContext';


import { initializeTheme } from './hooks/use-appearance';
import { CustomToast } from './components/custom-toast';
import { initializeGlobalSettings } from './utils/globalSettings';
import { applyRTLFromCookies } from './utils/rtl-utils';
import i18n from './i18n'; // Import i18n configuration
import './utils/axios-config'; // Import axios configuration




const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
        
    resolve: (name) => resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);
        
        // Make page data globally available for axios interceptor
        try {
            (window as any).page = props.initialPage;
        } catch (e) {
            console.warn('Could not set global page data:', e);
        }
        
        // Set demo mode globally
        try {
            (window as any).isDemo = props.initialPage.props?.is_demo || false;
        } catch (e) {
            // Ignore errors
        }
        

        
        // Initialize global settings from shared data
        const globalSettings = props.initialPage.props.globalSettings || {};
        if (Object.keys(globalSettings).length > 0) {
            initializeGlobalSettings(globalSettings);
        }
        
        // Apply RTL immediately on initial load
        applyRTLFromCookies(globalSettings);
        
        // Initialize language from cookie in demo mode
        const isDemo = props.initialPage.props?.is_demo || props.initialPage.props?.globalSettings?.is_demo || false;
        if (isDemo) {
            const getCookie = (name) => {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) {
                    const cookieValue = parts.pop()?.split(';').shift();
                    return cookieValue ? decodeURIComponent(cookieValue) : null;
                }
                return null;
            };
            
            const savedLang = getCookie('selected_language');
            if (savedLang && savedLang !== i18n.language) {
                i18n.changeLanguage(savedLang);
            }
        }
        
        // Apply RTL for Arabic/Hebrew languages
        const applyLanguageRTL = () => {
            const currentLang = i18n.language || 'en';
            const isRTLLang = ['ar', 'he'].includes(currentLang);
            const dir = isRTLLang ? 'rtl' : 'ltr';
            
            document.documentElement.dir = dir;
            document.documentElement.setAttribute('dir', dir);
            document.documentElement.lang = currentLang;
            document.body.dir = dir;
            document.body.setAttribute('dir', dir);
            
            if (isRTLLang) {
                document.documentElement.classList.add('rtl');
                document.body?.classList.add('rtl');
            } else {
                document.documentElement.classList.remove('rtl');
                document.body?.classList.remove('rtl');
            }
        };
        
        // Apply language RTL on initial load
        applyLanguageRTL();
        
        // Listen for language changes
        i18n.on('languageChanged', applyLanguageRTL);

        // Create a memoized render function to prevent unnecessary re-renders
        const renderApp = (appProps: any) => {
            const currentGlobalSettings = appProps.initialPage.props.globalSettings || {};
            const user = appProps.initialPage.props.auth?.user;
            
            return (
                <ModalStackProvider>
                        <LayoutProvider>
                            <SidebarProvider>
                                <BrandProvider globalSettings={currentGlobalSettings} user={user}>
                                    <Suspense fallback={<div className="flex h-screen w-full items-center justify-center">Loading...</div>}>
                                        <App {...appProps} />
                                    </Suspense>
                                    <CustomToast />
                                </BrandProvider>
                            </SidebarProvider>
                        </LayoutProvider>
                    </ModalStackProvider>
            );
        };
        
        // Initial render
        root.render(renderApp(props));
        
        // Update global page data on navigation and re-render with new settings
        router.on('navigate', (event) => {
            try {
                (window as any).page = event.detail.page;
                
                // Apply RTL immediately after navigation
                const newGlobalSettings = event.detail.page.props.globalSettings || {};
                applyRTLFromCookies(newGlobalSettings);
                
                // Also apply language-based RTL
                const currentLang = i18n.language || 'en';
                const isRTLLang = ['ar', 'he'].includes(currentLang);
                const dir = isRTLLang ? 'rtl' : 'ltr';
                
                document.documentElement.dir = dir;
                document.documentElement.setAttribute('dir', dir);
                document.body.dir = dir;
                document.body.setAttribute('dir', dir);
                
                if (isRTLLang) {
                    document.documentElement.classList.add('rtl');
                    document.body?.classList.add('rtl');
                } else {
                    document.documentElement.classList.remove('rtl');
                    document.body?.classList.remove('rtl');
                }
                
                // Re-render with updated props including globalSettings
                root.render(renderApp({ initialPage: event.detail.page }));
                
            } catch (e) {
                console.error('Navigation error:', e);
            }
        });
    },
    progress: {
        color: '#4B5563',
    },
});



// Initialize theme on all pages
initializeTheme();