import { useState } from 'react';
import { PageTemplate } from '@/components/page-template';
import { usePage, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Search, Eye } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import axios from 'axios';

export default function AllUserLogs() {
  const { t } = useTranslation();
  const { loginHistories, filters = {} } = usePage().props as any;
  
  const [searchTerm, setSearchTerm] = useState(filters.search || '');
  const [selectedHistory, setSelectedHistory] = useState<any>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    router.get(route('users.all-logs'), { 
      search: searchTerm,
      per_page: filters.per_page || 10
    });
  };

  const handleViewDetails = async (historyId: number) => {
    setIsLoading(true);
    try {
      const response = await axios.get(route('login-histories.show', historyId));
      setSelectedHistory(response.data.loginHistory);
      setIsModalOpen(true);
    } catch (error) {
      console.error('Failed to fetch login history details:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const breadcrumbs = [
    { title: t('Dashboard'), href: route('dashboard') },
    { title: t('User Management') },
    { title: t('All User Logs') }
  ];

  const formatLocation = (details: any) => {
    if (!details) return 'Unknown';
    
    const parts = [];
    if (details.city && details.city !== 'Unknown') parts.push(details.city);
    if (details.regionName && details.regionName !== 'Unknown') parts.push(details.regionName);
    if (details.country && details.country !== 'Unknown') parts.push(details.country);
    
    return parts.length > 0 ? parts.join(', ') : 'Unknown';
  };

  const formatDevice = (details: any) => {
    if (!details) return 'Unknown';
    
    const browser = details.browser_name || 'Unknown';
    const os = details.os_name || 'Unknown';
    const deviceType = details.device_type || 'desktop';
    
    return `${browser} on ${os} (${deviceType})`;
  };

  // Define table columns
  const columns = [
    { 
      key: 'user', 
      label: t('User'),
      render: (value: any, row: any) => (
        <div>
          <div className="font-medium text-gray-900">
            {row.user?.name || 'Unknown User'}
          </div>
          <div className="text-sm text-gray-500">
            {row.user?.email || 'No email'}
          </div>
        </div>
      )
    },
    { 
      key: 'ip', 
      label: t('IP Address'),
      render: (value: string) => <span className="text-gray-900">{value}</span>
    },
    { 
      key: 'location_device', 
      label: t('Location & Device'),
      render: (value: any, row: any) => (
        <div>
          <div className="text-gray-900">
            {formatLocation(row.details)}
          </div>
          <div className="text-sm text-gray-500">
            {formatDevice(row.details)}
          </div>
        </div>
      )
    },
    { 
      key: 'type', 
      label: t('Role'),
      render: (value: string) => <span className="text-gray-900">{value}</span>
    },
    { 
      key: 'created_at', 
      label: t('Time'),
      render: (value: string) => (
        <span className="text-gray-900">
          {new Date(value).toLocaleString()}
        </span>
      )
    }
  ];

  return (
    <PageTemplate 
      title={t("All User Logs")} 
      breadcrumbs={breadcrumbs}
      noPadding
    >
      {/* Search section */}
      <div className="bg-white rounded-lg shadow mb-4">
        <div className="p-4">
          <div className="flex items-center justify-between">
            <form onSubmit={handleSearch} className="flex gap-2">
              <div className="relative w-64">
                <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                <Input
                  placeholder={t("Search users, emails, IPs...")}
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  className="w-full pl-9"
                />
              </div>
              <Button type="submit" size="sm">
                <Search className="h-4 w-4 mr-1.5" />
                {t("Search")}
              </Button>
            </form>
            
            <div className="flex items-center gap-2">
              <Label className="text-xs text-muted-foreground">{t("Per Page:")}</Label>
              <Select 
                value={filters.per_page?.toString() || "10"} 
                onValueChange={(value) => {
                  router.get(route('users.all-logs'), { 
                    search: searchTerm,
                    per_page: parseInt(value) 
                  });
                }}
              >
                <SelectTrigger className="w-16 h-8">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="10">10</SelectItem>
                  <SelectItem value="25">25</SelectItem>
                  <SelectItem value="50">50</SelectItem>
                  <SelectItem value="100">100</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
        </div>
      </div>

      {/* Table section */}
      <div className="bg-white rounded-lg shadow overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="border-b bg-gray-50">
                {columns.map((column) => (
                  <th 
                    key={column.key} 
                    className="px-4 py-3 text-left font-medium text-gray-500"
                  >
                    {column.label}
                  </th>
                ))}
                <th className="px-4 py-3 text-right font-medium text-gray-500">
                  {t("Actions")}
                </th>
              </tr>
            </thead>
            <tbody>
              {loginHistories?.data?.map((history: any) => (
                <tr key={history.id} className="border-b hover:bg-gray-50">
                  {columns.map((column) => (
                    <td key={`${history.id}-${column.key}`} className="px-4 py-3">
                      {column.render ? column.render(history[column.key], history) : history[column.key]}
                    </td>
                  ))}
                  <td className="px-4 py-3 text-right">
                    <Button 
                      variant="ghost" 
                      size="icon"
                      className="text-blue-500 hover:text-blue-700"
                      onClick={() => handleViewDetails(history.id)}
                      disabled={isLoading}
                    >
                      <Eye className="h-4 w-4" />
                    </Button>
                  </td>
                </tr>
              ))}
              
              {(!loginHistories?.data || loginHistories.data.length === 0) && (
                <tr>
                  <td colSpan={columns.length + 1} className="px-4 py-8 text-center text-gray-500">
                    {t("No login histories found")}
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>

        {/* Pagination section */}
        <div className="p-4 border-t flex items-center justify-between">
          <div className="text-sm text-muted-foreground">
            {t("Showing")} <span className="font-medium">{loginHistories?.from || 0}</span> {t("to")} <span className="font-medium">{loginHistories?.to || 0}</span> {t("of")} <span className="font-medium">{loginHistories?.total || 0}</span> {t("results")}
          </div>
          
          <div className="flex gap-1">
            {loginHistories?.links?.map((link: any, i: number) => {
              const isTextLink = link.label === "&laquo; Previous" || link.label === "Next &raquo;";
              const label = link.label.replace("&laquo; ", "").replace(" &raquo;", "");
              
              return (
                <Button
                  key={i}
                  variant={link.active ? 'default' : 'outline'}
                  size={isTextLink ? "sm" : "icon"}
                  className={isTextLink ? "px-3" : "h-8 w-8"}
                  disabled={!link.url}
                  onClick={() => link.url && router.get(link.url)}
                >
                  {isTextLink ? label : <span dangerouslySetInnerHTML={{ __html: link.label }} />}
                </Button>
              );
            })}
          </div>
        </div>
      </div>

      {/* Login History Details Modal */}
      <Dialog open={isModalOpen} onOpenChange={setIsModalOpen}>
        <DialogContent className="max-w-2xl max-h-[80vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              {t('Login Details')}
            </DialogTitle>
          </DialogHeader>
          
          {selectedHistory && (
            <div className="space-y-4">
              <div className="overflow-x-auto">
                <table className="w-full text-sm border-collapse">
                  <tbody>
                    <tr className="border-b">
                      <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('User Name')}</td>
                      <td className="py-2 px-3 text-gray-900">{selectedHistory.user?.name || 'Unknown'}</td>
                    </tr>
                    <tr className="border-b">
                      <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Email')}</td>
                      <td className="py-2 px-3 text-gray-900">{selectedHistory.user?.email || 'No email'}</td>
                    </tr>
                    <tr className="border-b">
                      <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Role')}</td>
                      <td className="py-2 px-3 text-gray-900">{selectedHistory.type}</td>
                    </tr>
                    <tr className="border-b">
                      <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Login Time')}</td>
                      <td className="py-2 px-3 text-gray-900">{new Date(selectedHistory.created_at).toLocaleString()}</td>
                    </tr>
                    <tr className="border-b">
                      <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('IP Address')}</td>
                      <td className="py-2 px-3 text-gray-900 font-mono">{selectedHistory.ip}</td>
                    </tr>
                    <tr className="border-b">
                      <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Country')}</td>
                      <td className="py-2 px-3 text-gray-900">{selectedHistory.details?.country || 'Unknown'}</td>
                    </tr>
                    <tr className="border-b">
                      <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Region')}</td>
                      <td className="py-2 px-3 text-gray-900">{selectedHistory.details?.regionName || 'Unknown'}</td>
                    </tr>
                    <tr className="border-b">
                      <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('City')}</td>
                      <td className="py-2 px-3 text-gray-900">{selectedHistory.details?.city || 'Unknown'}</td>
                    </tr>
                    <tr className="border-b">
                      <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Browser')}</td>
                      <td className="py-2 px-3 text-gray-900">{selectedHistory.details?.browser_name || 'Unknown'}</td>
                    </tr>
                    <tr className="border-b">
                      <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Operating System')}</td>
                      <td className="py-2 px-3 text-gray-900">{selectedHistory.details?.os_name || 'Unknown'}</td>
                    </tr>
                    <tr className="border-b">
                      <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Device Type')}</td>
                      <td className="py-2 px-3 text-gray-900 capitalize">{selectedHistory.details?.device_type || 'Desktop'}</td>
                    </tr>
                    {selectedHistory.details?.timezone && (
                      <tr className="border-b">
                        <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Timezone')}</td>
                        <td className="py-2 px-3 text-gray-900">{selectedHistory.details.timezone}</td>
                      </tr>
                    )}
                    {selectedHistory.details?.isp && (
                      <tr className="border-b">
                        <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('ISP')}</td>
                        <td className="py-2 px-3 text-gray-900">{selectedHistory.details.isp}</td>
                      </tr>
                    )}
                    {selectedHistory.details?.org && (
                      <tr className="border-b">
                        <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Organization')}</td>
                        <td className="py-2 px-3 text-gray-900">{selectedHistory.details.org}</td>
                      </tr>
                    )}
                    {selectedHistory.details?.referrer_host && (
                      <tr className="border-b">
                        <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Referrer Host')}</td>
                        <td className="py-2 px-3 text-gray-900 font-mono">{selectedHistory.details.referrer_host}</td>
                      </tr>
                    )}
                    {selectedHistory.details?.referrer_path && (
                      <tr className="border-b">
                        <td className="py-2 px-3 font-medium text-gray-600 bg-gray-50">{t('Referrer Path')}</td>
                        <td className="py-2 px-3 text-gray-900 font-mono break-all">{selectedHistory.details.referrer_path}</td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}
        </DialogContent>
      </Dialog>
    </PageTemplate>
  );
}