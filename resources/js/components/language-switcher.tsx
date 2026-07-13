import React, { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import ReactCountryFlag from 'react-country-flag';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuTrigger,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { Globe, Plus } from 'lucide-react';
import { usePage } from '@inertiajs/react';
import { hasRole } from '@/utils/authorization';
import { CreateLanguageModal } from '@/components/create-language-modal';

interface Language {
    code: string;
    name: string;
    countryCode: string;
    enabled?: boolean;
}
export const LanguageSwitcher: React.FC = () => {
    const { t, i18n } = useTranslation();
    const { auth, is_saas, globalSettings } = usePage().props as any;
    const [showCreateModal, setShowCreateModal] = useState(false);
    const [currentLanguage, setCurrentLanguage] = useState<Language | null>(null);
    
    const availableLanguages = globalSettings?.availableLanguages || [];
    
    useEffect(() => {
        const languages = (availableLanguages || []).filter((l: any) => l.enabled !== false);
        const lang = languages.find((l: Language) => l.code === i18n.language) || languages[0];
        setCurrentLanguage(lang);
    }, [i18n.language, availableLanguages]);

    // Note: Removed automatic language switching when disabled
    // Users can keep their selected language even if disabled by superadmin

    const isAuthenticated = auth?.user;
    const userRoles = auth?.user?.roles?.map((role: any) => role.name) || [];
    const isSuperAdmin = isAuthenticated && hasRole('superadmin', userRoles);
    
    // Allow company users to manage languages when not in SaaS mode
    const isCompanyUser = userRoles.includes('company');
    const canManageLanguages = isSuperAdmin || (!is_saas && isCompanyUser);
    


    const handleLanguageChange = (languageCode: string) => {
        const lang = availableLanguages.find((l: Language) => l.code === languageCode);
        if (lang) {
            setCurrentLanguage(lang);
            i18n.changeLanguage(languageCode);
        }
    };

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="ghost" className="flex items-center gap-2 h-8 rounded-md">
                    <Globe className="h-4 w-4" />
                    {currentLanguage && (
                        <>
                            <span className="text-sm font-medium hidden md:inline-block">
                                {currentLanguage.name}
                            </span>
                            <ReactCountryFlag
                                countryCode={currentLanguage.countryCode}
                                svg
                                style={{
                                    width: '1.2em',
                                    height: '1.2em',
                                }}
                            />
                        </>
                    )}
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent className="w-56" align="end" forceMount>
                <DropdownMenuGroup>
                    <div className="max-h-48 overflow-y-auto">
                        {(availableLanguages || []).filter((language: any) => language.enabled !== false).map((language: Language) => (
                            <DropdownMenuItem
                                key={language.code}
                                onClick={() => handleLanguageChange(language.code)}
                                className={`flex items-center gap-2 ${currentLanguage?.code === language.code ? 'bg-accent' : ''}`}
                            >
                                <ReactCountryFlag
                                    countryCode={language.countryCode}
                                    svg
                                    style={{
                                        width: '1.2em',
                                        height: '1.2em',
                                    }}
                                />
                                <span>{language.name}</span>
                            </DropdownMenuItem>
                        ))}
                    </div>
                </DropdownMenuGroup>
                {canManageLanguages && (
                    <>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem 
                            onClick={() => setShowCreateModal(true)}
                            className="justify-center text-primary font-semibold cursor-pointer"
                        >
                            <Plus className="h-4 w-4 mr-2" />
                            {t('Create Language')}
                        </DropdownMenuItem>
                        <DropdownMenuItem asChild className="justify-center text-primary font-semibold cursor-pointer">
                            <a href={route('manage-language')} rel="noopener noreferrer">
                                {t('Manage Language')}
                            </a>
                        </DropdownMenuItem>
                    </>
                )}
            </DropdownMenuContent>
            <CreateLanguageModal
                open={showCreateModal}
                onOpenChange={setShowCreateModal}
                onSuccess={() => setShowCreateModal(false)}
            />
        </DropdownMenu>
    );
};