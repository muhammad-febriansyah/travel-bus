import { useState } from 'react';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { User, Mail, Phone, Calendar, MapPin, ArrowRight } from 'lucide-react';

interface RouteBookingModalProps {
    isOpen: boolean;
    onClose: () => void;
    route: {
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
    };
    whatsappNumber?: string;
}

export default function RouteBookingModal({ isOpen, onClose, route, whatsappNumber }: RouteBookingModalProps) {
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
        date: '',
        category: route.prices[0]?.category || '',
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

    const selectedPrice = route.prices.find(p => p.category === formData.category)?.price || 0;
    const totalPrice = selectedPrice * parseInt(formData.passengers || '1');

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        // Submit to backend
        try {
            const response = await fetch('/api/bookings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    route_id: route.id,
                    customer_name: formData.name,
                    customer_email: formData.email,
                    customer_phone: formData.phone,
                    travel_date: formData.date,
                    category: formData.category,
                    total_passengers: formData.passengers,
                    notes: formData.notes,
                }),
            });

            if (response.ok) {
                alert('Booking berhasil disimpan!');
                onClose();
            } else {
                alert('Terjadi kesalahan saat menyimpan booking.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan booking.');
        }
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="max-h-[90vh] overflow-y-auto sm:max-w-[600px]">
                <DialogHeader>
                    <DialogTitle className="text-2xl font-bold text-gray-900">
                        Form Pemesanan Rute
                    </DialogTitle>
                    <DialogDescription>
                        Lengkapi data Anda untuk melanjutkan pemesanan
                    </DialogDescription>
                </DialogHeader>

                {/* Route Summary */}
                <div className="rounded-xl border border-gray-200 bg-gradient-to-br from-[#2547F9]/5 to-indigo-50 p-4">
                    <div className="flex items-center justify-between text-sm">
                        <div className="flex items-center gap-2">
                            <MapPin className="h-4 w-4 text-[#2547F9]" />
                            <span className="font-semibold text-gray-900">{route.origin}</span>
                        </div>
                        <ArrowRight className="h-4 w-4 text-gray-400" />
                        <div className="flex items-center gap-2">
                            <MapPin className="h-4 w-4 text-[#2547F9]" />
                            <span className="font-semibold text-gray-900">{route.destination}</span>
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
                                placeholder="1"
                                value={formData.passengers}
                                onChange={handleChange}
                                required
                            />
                        </div>
                    </div>

                    <div className="space-y-2">
                        <Label htmlFor="category">Kategori Armada *</Label>
                        <select
                            id="category"
                            name="category"
                            value={formData.category}
                            onChange={handleChange}
                            className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            required
                        >
                            {route.prices.map((price, index) => (
                                <option key={index} value={price.category}>
                                    {price.category} - {formatPrice(price.price)}
                                </option>
                            ))}
                        </select>
                    </div>

                    {/* Price Summary */}
                    <div className="rounded-lg bg-gray-50 p-4">
                        <div className="space-y-2 text-sm">
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

                    <div className="space-y-2">
                        <Label htmlFor="notes">Catatan Tambahan</Label>
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
