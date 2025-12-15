import { useState, useEffect } from 'react';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { UserPlus, Check, Search } from 'lucide-react';
import { toast } from 'sonner';
import axios from 'axios';

interface Customer {
    id: number;
    name: string;
    phone: string;
    email?: string;
}

interface Props {
    onCustomerSelect: (customerId: number) => void;
}

export function CustomerSearch({ onCustomerSelect }: Props) {
    const [customers, setCustomers] = useState<Customer[]>([]);
    const [allCustomers, setAllCustomers] = useState<Customer[]>([]);
    const [selectedCustomer, setSelectedCustomer] = useState<Customer | null>(null);
    const [searchValue, setSearchValue] = useState('');
    const [loading, setLoading] = useState(false);
    const [showNewCustomerDialog, setShowNewCustomerDialog] = useState(false);
    const [newCustomer, setNewCustomer] = useState({
        name: '',
        phone: '',
        email: '',
    });
    const [creating, setCreating] = useState(false);

    // Load all customers on mount
    useEffect(() => {
        loadAllCustomers();
    }, []);

    const loadAllCustomers = async () => {
        setLoading(true);
        try {
            const response = await axios.get('/admin/seat-booking/customers/search', {
                params: { search: 'a' }, // Get all
            });
            setAllCustomers(response.data);
            setCustomers(response.data);
        } catch (error) {
            console.error('Failed to load customers:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleSearch = (value: string) => {
        setSearchValue(value);

        if (!value) {
            setCustomers(allCustomers);
            return;
        }

        const filtered = allCustomers.filter(customer =>
            customer.name.toLowerCase().includes(value.toLowerCase()) ||
            customer.phone.includes(value) ||
            customer.email?.toLowerCase().includes(value.toLowerCase())
        );
        setCustomers(filtered);
    };

    const handleSelectCustomer = (customerId: string) => {
        const customer = customers.find(c => c.id === parseInt(customerId));
        if (customer) {
            setSelectedCustomer(customer);
            onCustomerSelect(customer.id);
        }
    };

    const handleCreateCustomer = async () => {
        if (!newCustomer.name || !newCustomer.phone) {
            toast.error('Data tidak lengkap', {
                description: 'Nama dan nomor telepon wajib diisi'
            });
            return;
        }

        setCreating(true);
        const loadingToast = toast.loading('Membuat customer baru...', {
            description: 'Mohon tunggu sebentar'
        });

        try {
            const response = await axios.post('/admin/seat-booking/customers', newCustomer);

            if (response.data.success) {
                const customer = response.data.customer;
                setAllCustomers([customer, ...allCustomers]);
                setCustomers([customer, ...customers]);
                setSelectedCustomer(customer);
                onCustomerSelect(customer.id);
                setShowNewCustomerDialog(false);
                setNewCustomer({ name: '', phone: '', email: '' });

                toast.dismiss(loadingToast);
                toast.success('Customer berhasil dibuat!', {
                    description: `${customer.name} - ${customer.phone}`
                });
            }
        } catch (error: any) {
            toast.dismiss(loadingToast);
            console.error('Failed to create customer:', error);

            const errorMessage = error.response?.data?.message || error.message;
            toast.error('Gagal membuat customer', {
                description: errorMessage,
                duration: 5000,
            });
        } finally {
            setCreating(false);
        }
    };

    if (selectedCustomer) {
        return (
            <div className="flex items-center gap-2 p-3 border rounded-lg bg-green-50 dark:bg-green-950/20 border-green-200 dark:border-green-800">
                <Check className="h-5 w-5 text-green-600 dark:text-green-400 flex-shrink-0" />
                <div className="flex-1">
                    <div className="font-medium text-green-900 dark:text-green-100">
                        {selectedCustomer.name}
                    </div>
                    <div className="text-sm text-green-700 dark:text-green-300">
                        {selectedCustomer.phone}
                        {selectedCustomer.email && ` • ${selectedCustomer.email}`}
                    </div>
                </div>
                <Button
                    variant="ghost"
                    size="sm"
                    onClick={() => {
                        setSelectedCustomer(null);
                        onCustomerSelect(0);
                    }}
                >
                    Ganti
                </Button>
            </div>
        );
    }

    return (
        <div className="space-y-2">
            <div className="space-y-2">
                <div className="relative">
                    <Search className="absolute left-3 top-3 h-4 w-4 text-muted-foreground z-10" />
                    <Input
                        type="text"
                        placeholder="Cari customer..."
                        value={searchValue}
                        onChange={(e) => handleSearch(e.target.value)}
                        className="pl-9"
                    />
                </div>

                <Select onValueChange={handleSelectCustomer}>
                    <SelectTrigger>
                        <SelectValue placeholder="Pilih customer" />
                    </SelectTrigger>
                    <SelectContent className="max-h-[300px]">
                        {loading ? (
                            <div className="p-2 text-sm text-center text-muted-foreground">
                                Loading...
                            </div>
                        ) : customers.length > 0 ? (
                            customers.map(customer => (
                                <SelectItem key={customer.id} value={customer.id.toString()}>
                                    <div className="flex flex-col">
                                        <span className="font-medium">{customer.name}</span>
                                        <span className="text-xs text-muted-foreground">
                                            {customer.phone}
                                            {customer.email && ` • ${customer.email}`}
                                        </span>
                                    </div>
                                </SelectItem>
                            ))
                        ) : (
                            <div className="p-2 text-sm text-center text-muted-foreground">
                                Tidak ada customer ditemukan
                            </div>
                        )}
                    </SelectContent>
                </Select>
            </div>

            <Dialog open={showNewCustomerDialog} onOpenChange={setShowNewCustomerDialog}>
                <DialogTrigger asChild>
                    <Button type="button" variant="outline" className="w-full">
                        <UserPlus className="mr-2 h-4 w-4" />
                        Buat Customer Baru
                    </Button>
                </DialogTrigger>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Buat Customer Baru</DialogTitle>
                        <DialogDescription>
                            Masukkan data customer yang akan melakukan booking
                        </DialogDescription>
                    </DialogHeader>
                    <div className="space-y-4 py-4">
                        <div className="space-y-2">
                            <Label htmlFor="new_name">Nama *</Label>
                            <Input
                                id="new_name"
                                value={newCustomer.name}
                                onChange={(e) => setNewCustomer({ ...newCustomer, name: e.target.value })}
                                placeholder="Nama lengkap"
                            />
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="new_phone">No. HP *</Label>
                            <Input
                                id="new_phone"
                                type="tel"
                                value={newCustomer.phone}
                                onChange={(e) => setNewCustomer({ ...newCustomer, phone: e.target.value })}
                                placeholder="08xxx"
                            />
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="new_email">Email</Label>
                            <Input
                                id="new_email"
                                type="email"
                                value={newCustomer.email}
                                onChange={(e) => setNewCustomer({ ...newCustomer, email: e.target.value })}
                                placeholder="email@example.com (opsional)"
                            />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => setShowNewCustomerDialog(false)}
                            disabled={creating}
                        >
                            Batal
                        </Button>
                        <Button
                            type="button"
                            onClick={handleCreateCustomer}
                            disabled={creating}
                        >
                            {creating ? 'Membuat...' : 'Buat Customer'}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    );
}
