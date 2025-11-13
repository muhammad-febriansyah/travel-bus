import { useState } from 'react';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Users, Car, MessageCircle, User, Mail, Phone, Calendar, MapPin } from 'lucide-react';
import { toast } from 'sonner';
import { router } from '@inertiajs/react';

interface Route {
    id: number;
    origin: string;
    destination: string;
    route_code: string;
    distance: number;
    estimated_duration: number;
    prices: Array<{
        category: string;
        price: number;
    }>;
}

interface BookingModalProps {
    isOpen: boolean;
    onClose: () => void;
    armada: {
        id: number;
        name: string;
        vehicle_type: string;
        capacity: number;
        category: string;
        description: string;
        image: string | null;
    };
    routes: Route[];
    whatsappNumber?: string;
}

export default function BookingModal({ isOpen, onClose, armada, routes, whatsappNumber }: BookingModalProps) {
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
        date: '',
        route_id: routes[0]?.id.toString() || '',
        passengers: '1',
        notes: '',
    });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value,
        });
    };

    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(price);
    };

    const selectedRoute = routes.find(r => r.id.toString() === formData.route_id);
    const selectedPrice = selectedRoute?.prices.find(p => p.category === armada.category)?.price || 0;
    const totalPrice = selectedPrice * parseInt(formData.passengers || '1');

    const handleWhatsAppBooking = () => {
        const message = `Halo, saya ingin memesan armada:

*Detail Armada:*
- Nama: ${armada.name}
- Tipe: ${armada.vehicle_type}
- Kapasitas: ${armada.capacity} orang
- Kategori: ${armada.category}

*Data Pemesan:*
- Nama: ${formData.name || '-'}
- Email: ${formData.email || '-'}
- No. HP: ${formData.phone || '-'}
- Tanggal: ${formData.date || '-'}
- Catatan: ${formData.notes || '-'}

Mohon informasi lebih lanjut. Terima kasih!`;

        const encodedMessage = encodeURIComponent(message);
        const waNumber = whatsappNumber || '6281234567890';
        window.open(`https://wa.me/${waNumber}?text=${encodedMessage}`, '_blank');
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        const loadingToast = toast.loading('Menyimpan booking...');

        router.post('/api/bookings', {
            armada_id: armada.id,
            route_id: formData.route_id,
            category: armada.category,
            customer_name: formData.name,
            customer_email: formData.email,
            customer_phone: formData.phone,
            travel_date: formData.date,
            total_passengers: formData.passengers,
            total_price: totalPrice,
            notes: formData.notes,
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                toast.dismiss(loadingToast);
                toast.success('Booking berhasil disimpan!', {
                    description: `Booking untuk ${armada.name} telah tersimpan. Kami akan menghubungi Anda segera.`,
                });
                onClose();
                // Reset form
                setFormData({
                    name: '',
                    email: '',
                    phone: '',
                    date: '',
                    route_id: routes[0]?.id.toString() || '',
                    passengers: '1',
                    notes: '',
                });
            },
            onError: (errors) => {
                toast.dismiss(loadingToast);
                const errorMessage = Object.values(errors)[0] as string || 'Terjadi kesalahan saat menyimpan booking.';
                toast.error('Gagal menyimpan booking', {
                    description: errorMessage,
                });
            },
        });
    };

    const handleWhatsAppOnly = () => {
        const message = `Halo, saya ingin menanyakan tentang armada:

*Detail Armada:*
- Nama: ${armada.name}
- Tipe: ${armada.vehicle_type}
- Kapasitas: ${armada.capacity} orang
- Kategori: ${armada.category}

Mohon informasi lebih lanjut untuk pemesanan. Terima kasih!`;

        const encodedMessage = encodeURIComponent(message);
        const waNumber = whatsappNumber || '6281234567890';
        window.open(`https://wa.me/${waNumber}?text=${encodedMessage}`, '_blank');
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="max-h-[90vh] overflow-y-auto sm:max-w-[600px]">
                <DialogHeader>
                    <DialogTitle className="text-2xl font-bold text-gray-900">
                        Detail & Pemesanan Armada
                    </DialogTitle>
                    <DialogDescription>
                        Lengkapi data Anda untuk melanjutkan pemesanan
                    </DialogDescription>
                </DialogHeader>

                {/* Armada Details */}
                <div className="rounded-xl border border-gray-200 bg-gradient-to-br from-[#2547F9]/5 to-indigo-50 p-6">
                    {armada.image && (
                        <div className="mb-4 overflow-hidden rounded-lg">
                            <img
                                src={armada.image}
                                alt={armada.name}
                                className="h-48 w-full object-cover"
                            />
                        </div>
                    )}
                    <h3 className="mb-3 text-xl font-bold text-gray-900">{armada.name}</h3>
                    <div className="space-y-2 text-sm">
                        <div className="flex items-center gap-2 text-gray-700">
                            <Car className="h-4 w-4 text-[#2547F9]" />
                            <span>{armada.vehicle_type}</span>
                        </div>
                        <div className="flex items-center gap-2 text-gray-700">
                            <Users className="h-4 w-4 text-[#2547F9]" />
                            <span>Kapasitas: {armada.capacity} orang</span>
                        </div>
                        <div className="mt-3 rounded-lg bg-white/80 p-3">
                            <p className="text-sm text-gray-600">{armada.description}</p>
                        </div>
                    </div>
                </div>

                {/* Booking Form */}
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="grid gap-4 sm:grid-cols-2">
                        <div className="space-y-2">
                            <Label htmlFor="name" className="flex items-center gap-2">
                                <User className="h-4 w-4 text-[#2547F9]" />
                                Nama Lengkap *
                            </Label>
                            <Input
                                id="name"
                                name="name"
                                placeholder="Masukkan nama lengkap"
                                value={formData.name}
                                onChange={handleChange}
                                required
                            />
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="phone" className="flex items-center gap-2">
                                <Phone className="h-4 w-4 text-[#2547F9]" />
                                No. Telepon *
                            </Label>
                            <Input
                                id="phone"
                                name="phone"
                                type="tel"
                                placeholder="08123456789"
                                value={formData.phone}
                                onChange={handleChange}
                                required
                            />
                        </div>
                    </div>

                    <div className="space-y-2">
                        <Label htmlFor="email" className="flex items-center gap-2">
                            <Mail className="h-4 w-4 text-[#2547F9]" />
                            Email
                        </Label>
                        <Input
                            id="email"
                            name="email"
                            type="email"
                            placeholder="email@example.com"
                            value={formData.email}
                            onChange={handleChange}
                        />
                    </div>

                    <div className="space-y-2">
                        <Label htmlFor="route_id" className="flex items-center gap-2">
                            <MapPin className="h-4 w-4 text-[#2547F9]" />
                            Pilih Rute *
                        </Label>
                        <select
                            id="route_id"
                            name="route_id"
                            value={formData.route_id}
                            onChange={handleChange}
                            className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            required
                        >
                            {routes.map((route) => (
                                <option key={route.id} value={route.id}>
                                    {route.origin} → {route.destination} ({route.route_code})
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="grid gap-4 sm:grid-cols-2">
                        <div className="space-y-2">
                            <Label htmlFor="date" className="flex items-center gap-2">
                                <Calendar className="h-4 w-4 text-[#2547F9]" />
                                Tanggal Keberangkatan *
                            </Label>
                            <Input
                                id="date"
                                name="date"
                                type="date"
                                value={formData.date}
                                onChange={handleChange}
                                required
                            />
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="passengers">Jumlah Penumpang *</Label>
                            <Input
                                id="passengers"
                                name="passengers"
                                type="number"
                                min="1"
                                max={armada.capacity}
                                placeholder="1"
                                value={formData.passengers}
                                onChange={handleChange}
                                required
                            />
                        </div>
                    </div>

                    {/* Price Summary */}
                    {selectedRoute && selectedPrice > 0 && (
                        <div className="rounded-lg bg-gray-50 p-4">
                            <div className="space-y-2 text-sm">
                                <div className="flex justify-between">
                                    <span className="text-gray-600">Rute:</span>
                                    <span className="font-semibold">
                                        {selectedRoute.origin} → {selectedRoute.destination}
                                    </span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-600">Kategori:</span>
                                    <span className="font-semibold">{armada.category}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-600">Harga per orang:</span>
                                    <span className="font-semibold">{formatPrice(selectedPrice)}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-600">Jumlah penumpang:</span>
                                    <span className="font-semibold">{formData.passengers} orang</span>
                                </div>
                                <div className="flex justify-between border-t pt-2">
                                    <span className="font-semibold text-gray-900">Total Estimasi:</span>
                                    <span className="text-lg font-bold text-[#2547F9]">
                                        {formatPrice(totalPrice)}
                                    </span>
                                </div>
                            </div>
                        </div>
                    )}

                    <div className="space-y-2">
                        <Label htmlFor="notes" className="flex items-center gap-2">
                            <MessageCircle className="h-4 w-4 text-[#2547F9]" />
                            Catatan Tambahan
                        </Label>
                        <Textarea
                            id="notes"
                            name="notes"
                            placeholder="Masukkan catatan atau permintaan khusus..."
                            rows={3}
                            value={formData.notes}
                            onChange={handleChange}
                        />
                    </div>

                    {/* Action Buttons */}
                    <div className="flex flex-col gap-3 pt-4">
                        <Button
                            type="submit"
                            className="bg-[#2547F9] text-white hover:bg-[#1d3acc]"
                        >
                            Simpan Booking
                        </Button>
                        <Button
                            type="button"
                            variant="outline"
                            onClick={onClose}
                            className="border-gray-300"
                        >
                            Batal
                        </Button>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    );
}
