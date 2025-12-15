import { useState, useEffect } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Calendar, Clock, MapPin, Users, DollarSign, Loader2, CheckCircle, User, Phone, Mail } from 'lucide-react';
import { toast } from 'sonner';
import axios from 'axios';
import { router } from '@inertiajs/react';

interface Route {
    id: number;
    origin: string;
    destination: string;
}

interface Category {
    id: number;
    name: string;
}

interface Armada {
    id: number;
    name: string;
    capacity: number;
    seat_layout_id: number | null;
    category_id: number;
    plate_number: string;
    category?: Category;
}

interface Props {
    routes: Route[];
    armadas: Armada[];
    categories: Category[];
    selectedSeats: string[];
    totalPassengers: number;
    onTotalPassengersChange: (count: number) => void;
    onArmadaChange: (armada: Armada | null) => void;
    onDateChange: (date: string) => void;
    onTimeChange: (time: string) => void;
    travelDate: string;
    travelTime: string;
}

export function CustomerBookingForm({
    routes,
    armadas,
    categories,
    selectedSeats,
    totalPassengers,
    onTotalPassengersChange,
    onArmadaChange,
    onDateChange,
    onTimeChange,
    travelDate,
    travelTime,
}: Props) {
    // Customer info
    const [customerName, setCustomerName] = useState('');
    const [customerPhone, setCustomerPhone] = useState('');
    const [customerEmail, setCustomerEmail] = useState('');

    // Booking info
    const [selectedArmada, setSelectedArmada] = useState<Armada | null>(null);
    const [routeId, setRouteId] = useState('');
    const [pricePerPerson, setPricePerPerson] = useState(0);
    const [priceDiscount, setPriceDiscount] = useState(0);
    const [priceLoading, setPriceLoading] = useState(false);
    const [pickupLocation, setPickupLocation] = useState('');
    const [notes, setNotes] = useState('');
    const [submitting, setSubmitting] = useState(false);
    const [success, setSuccess] = useState(false);

    const totalPrice = pricePerPerson * totalPassengers;

    // Fetch price from database when route, category, and date are selected
    const fetchPrice = async () => {
        if (!routeId || !selectedArmada || !travelDate) {
            setPricePerPerson(0);
            setPriceDiscount(0);
            return;
        }

        setPriceLoading(true);
        try {
            const response = await axios.get('/booking/price', {
                params: {
                    route_id: routeId,
                    category_id: selectedArmada.category_id,
                    travel_date: travelDate,
                }
            });

            setPricePerPerson(response.data.final_price);
            setPriceDiscount(response.data.discount);

            if (response.data.discount > 0) {
                toast.success('Harga berhasil dimuat', {
                    description: `Hemat Rp ${response.data.discount.toLocaleString('id-ID')}!`,
                });
            }
        } catch (error: any) {
            console.error('Failed to fetch price:', error);
            setPricePerPerson(0);
            setPriceDiscount(0);

            const errorMessage = error.response?.data?.error || 'Gagal memuat harga. Pastikan admin sudah mengatur harga untuk rute dan kategori ini.';

            toast.error('Harga belum tersedia', {
                description: errorMessage,
                duration: 5000,
            });
        } finally {
            setPriceLoading(false);
        }
    };

    // Auto-fetch price when route, armada, or date changes
    useEffect(() => {
        fetchPrice();
    }, [routeId, selectedArmada, travelDate]);

    const handleArmadaChange = (armadaId: string) => {
        const armada = armadas.find(a => a.id === parseInt(armadaId));
        setSelectedArmada(armada || null);
        onArmadaChange(armada || null);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        // Validation
        if (!customerName || !customerPhone) {
            toast.error('Data Customer Belum Lengkap', {
                description: 'Nama dan nomor HP wajib diisi'
            });
            return;
        }

        if (!routeId) {
            toast.error('Rute belum dipilih');
            return;
        }

        if (!selectedArmada) {
            toast.error('Armada belum dipilih');
            return;
        }

        if (!travelDate) {
            toast.error('Tanggal belum dipilih');
            return;
        }

        if (pricePerPerson <= 0) {
            toast.error('Harga belum diisi');
            return;
        }

        if (selectedSeats.length !== totalPassengers) {
            toast.error('Jumlah kursi tidak sesuai', {
                description: `Pilih ${totalPassengers} kursi`
            });
            return;
        }

        const formData = {
            // Customer info
            customer_name: customerName,
            customer_phone: customerPhone,
            customer_email: customerEmail || null,

            // Booking info
            route_id: routeId,
            armada_id: selectedArmada.id,
            category_id: selectedArmada.category_id,
            travel_date: travelDate,
            travel_time: travelTime || null,
            total_passengers: totalPassengers,
            selected_seats: selectedSeats,
            // NOTE: price_per_person and total_price are calculated server-side for security
            pickup_location: pickupLocation || null,
            notes: notes || null,
        };

        setSubmitting(true);
        const loadingToast = toast.loading('Membuat booking...', {
            description: 'Mohon tunggu sebentar'
        });

        try {
            const response = await axios.post('/booking/create', formData);

            if (response.data.success) {
                toast.dismiss(loadingToast);
                setSuccess(true);

                const bookingCode = response.data.booking_code;

                toast.success('Booking Berhasil!', {
                    description: `Kode booking: ${bookingCode}. Silakan transfer untuk konfirmasi.`,
                    duration: 7000,
                });

                // Redirect to booking detail after 2 seconds
                setTimeout(() => {
                    router.visit(`/booking/${bookingCode}`);
                }, 2000);
            }
        } catch (error: any) {
            toast.dismiss(loadingToast);
            console.error('Booking failed:', error);

            const errorMessage = error.response?.data?.error || error.response?.data?.message || error.message;

            toast.error('Gagal membuat booking', {
                description: errorMessage,
                duration: 5000,
            });
        } finally {
            setSubmitting(false);
        }
    };

    return (
        <Card className="border-0 shadow-xl overflow-hidden">
            <div className="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <CardTitle className="text-xl">Form Booking</CardTitle>
                <CardDescription className="text-blue-100 mt-1">Lengkapi data dan pilih kursi</CardDescription>
            </div>
            <CardContent className="p-6">
                <form onSubmit={handleSubmit} className="space-y-6">
                    {/* Customer Info Section */}
                    <div className="space-y-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-950/20">
                        <h3 className="font-semibold text-blue-900 dark:text-blue-100">Data Pemesan</h3>

                        <div className="space-y-2">
                            <Label htmlFor="customer_name" className="flex items-center gap-2">
                                <User className="h-4 w-4" />
                                Nama Lengkap *
                            </Label>
                            <Input
                                id="customer_name"
                                type="text"
                                value={customerName}
                                onChange={(e) => setCustomerName(e.target.value)}
                                placeholder="Masukkan nama lengkap"
                                required
                            />
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="customer_phone" className="flex items-center gap-2">
                                <Phone className="h-4 w-4" />
                                Nomor HP / WhatsApp *
                            </Label>
                            <Input
                                id="customer_phone"
                                type="tel"
                                value={customerPhone}
                                onChange={(e) => setCustomerPhone(e.target.value)}
                                placeholder="08xxxxxxxxxx"
                                required
                            />
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="customer_email" className="flex items-center gap-2">
                                <Mail className="h-4 w-4" />
                                Email (Opsional)
                            </Label>
                            <Input
                                id="customer_email"
                                type="email"
                                value={customerEmail}
                                onChange={(e) => setCustomerEmail(e.target.value)}
                                placeholder="email@example.com"
                            />
                        </div>
                    </div>

                    {/* Route */}
                    <div className="space-y-2">
                        <Label htmlFor="route_id">Rute *</Label>
                        <Select value={routeId} onValueChange={setRouteId} required>
                            <SelectTrigger id="route_id">
                                <SelectValue placeholder="Pilih rute" />
                            </SelectTrigger>
                            <SelectContent>
                                {routes.map(route => (
                                    <SelectItem key={route.id} value={route.id.toString()}>
                                        {route.origin} → {route.destination}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>

                    {/* Armada */}
                    <div className="space-y-2">
                        <Label htmlFor="armada_id">Armada *</Label>
                        <Select onValueChange={handleArmadaChange} required>
                            <SelectTrigger id="armada_id">
                                <SelectValue placeholder="Pilih armada" />
                            </SelectTrigger>
                            <SelectContent>
                                {armadas.map(armada => (
                                    <SelectItem key={armada.id} value={armada.id.toString()}>
                                        <div className="flex flex-col">
                                            <span className="font-medium">{armada.name}</span>
                                            <span className="text-xs text-muted-foreground">
                                                {armada.category?.name || 'N/A'} • {armada.plate_number} • {armada.capacity} kursi
                                            </span>
                                        </div>
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>

                    {/* Date & Time */}
                    <div className="grid gap-4 sm:grid-cols-2">
                        <div className="space-y-2">
                            <Label htmlFor="travel_date" className="flex items-center gap-2">
                                <Calendar className="h-4 w-4" />
                                Tanggal Perjalanan *
                            </Label>
                            <Input
                                id="travel_date"
                                type="date"
                                value={travelDate}
                                onChange={(e) => onDateChange(e.target.value)}
                                min={new Date().toISOString().split('T')[0]}
                                required
                            />
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="travel_time" className="flex items-center gap-2">
                                <Clock className="h-4 w-4" />
                                Waktu Keberangkatan
                            </Label>
                            <Input
                                id="travel_time"
                                type="time"
                                value={travelTime}
                                onChange={(e) => onTimeChange(e.target.value)}
                            />
                        </div>
                    </div>

                    {/* Passengers */}
                    <div className="space-y-2">
                        <Label htmlFor="total_passengers" className="flex items-center gap-2">
                            <Users className="h-4 w-4" />
                            Jumlah Penumpang *
                        </Label>
                        <Input
                            id="total_passengers"
                            type="number"
                            min="1"
                            max={selectedArmada?.capacity || 10}
                            value={totalPassengers}
                            onChange={(e) => onTotalPassengersChange(parseInt(e.target.value) || 1)}
                            required
                        />
                        <p className="text-xs text-muted-foreground">
                            Kursi dipilih: {selectedSeats.length} / {totalPassengers}
                            {selectedSeats.length === totalPassengers && (
                                <span className="ml-2 text-green-600">✓ Sesuai</span>
                            )}
                        </p>
                    </div>

                    {/* Pricing - Read Only (from database) */}
                    <div className="grid gap-4 sm:grid-cols-2">
                        <div className="space-y-2">
                            <Label htmlFor="price_per_person" className="flex items-center gap-2">
                                <DollarSign className="h-4 w-4" />
                                Harga per Orang
                            </Label>
                            <div className="relative">
                                <Input
                                    id="price_per_person"
                                    value={pricePerPerson > 0 ? `Rp ${pricePerPerson.toLocaleString('id-ID')}` : 'Pilih rute, armada, dan tanggal'}
                                    disabled
                                    className="bg-muted/50 font-semibold text-base"
                                />
                                {priceLoading && (
                                    <Loader2 className="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 animate-spin text-muted-foreground" />
                                )}
                            </div>
                            {priceDiscount > 0 && (
                                <p className="text-xs text-green-600 font-medium">
                                    🎉 Hemat Rp {priceDiscount.toLocaleString('id-ID')}!
                                </p>
                            )}
                            <p className="text-xs text-muted-foreground">
                                Harga otomatis dari admin
                            </p>
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="total_price">Total Harga</Label>
                            <Input
                                id="total_price"
                                value={totalPrice > 0 ? `Rp ${totalPrice.toLocaleString('id-ID')}` : 'Rp 0'}
                                disabled
                                className="bg-primary/10 font-bold text-lg text-primary"
                            />
                            <p className="text-xs text-muted-foreground">
                                {totalPassengers} penumpang × Rp {pricePerPerson.toLocaleString('id-ID')}
                            </p>
                        </div>
                    </div>

                    {/* Pickup Location */}
                    <div className="space-y-2">
                        <Label htmlFor="pickup_location" className="flex items-center gap-2">
                            <MapPin className="h-4 w-4" />
                            Lokasi Penjemputan (Opsional)
                        </Label>
                        <Input
                            id="pickup_location"
                            type="text"
                            value={pickupLocation}
                            onChange={(e) => setPickupLocation(e.target.value)}
                            placeholder="Alamat penjemputan"
                        />
                    </div>

                    {/* Notes */}
                    <div className="space-y-2">
                        <Label htmlFor="notes">Catatan (Opsional)</Label>
                        <Textarea
                            id="notes"
                            value={notes}
                            onChange={(e) => setNotes(e.target.value)}
                            placeholder="Catatan tambahan"
                            rows={3}
                        />
                    </div>

                    {/* Submit Button */}
                    <Button
                        type="submit"
                        className="w-full bg-green-600 hover:bg-green-700"
                        size="lg"
                        disabled={submitting || success || selectedSeats.length !== totalPassengers}
                    >
                        {submitting ? (
                            <>
                                <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                                Memproses...
                            </>
                        ) : success ? (
                            <>
                                <CheckCircle className="mr-2 h-4 w-4" />
                                Booking Berhasil!
                            </>
                        ) : (
                            'Buat Booking Sekarang'
                        )}
                    </Button>

                    <p className="text-xs text-center text-muted-foreground">
                        Dengan melakukan booking, Anda menyetujui syarat dan ketentuan yang berlaku
                    </p>
                </form>
            </CardContent>
        </Card>
    );
}
