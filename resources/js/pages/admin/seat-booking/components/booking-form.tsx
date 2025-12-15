import { useState, useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { CustomerSearch } from './customer-search';
import { Calendar, Clock, MapPin, Users, DollarSign, Loader2, CheckCircle } from 'lucide-react';
import { toast } from 'sonner';
import axios from 'axios';

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

export function BookingForm({
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
    const [selectedArmada, setSelectedArmada] = useState<Armada | null>(null);
    const [customerId, setCustomerId] = useState<number | null>(null);
    const [routeId, setRouteId] = useState<string>('');
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

            const errorMessage = error.response?.data?.error || 'Harga belum diatur di menu Harga. Silakan atur terlebih dahulu.';

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
        if (!customerId) {
            toast.error('Customer belum dipilih', {
                description: 'Silakan pilih customer terlebih dahulu'
            });
            return;
        }

        if (!routeId) {
            toast.error('Rute belum dipilih', {
                description: 'Silakan pilih rute terlebih dahulu'
            });
            return;
        }

        if (!selectedArmada) {
            toast.error('Armada belum dipilih', {
                description: 'Silakan pilih armada terlebih dahulu'
            });
            return;
        }

        if (!travelDate) {
            toast.error('Tanggal belum dipilih', {
                description: 'Silakan pilih tanggal perjalanan'
            });
            return;
        }

        if (pricePerPerson <= 0) {
            toast.error('Harga belum diisi', {
                description: 'Silakan isi harga per orang'
            });
            return;
        }

        if (selectedSeats.length !== totalPassengers) {
            toast.error('Jumlah kursi tidak sesuai', {
                description: `Pilih ${totalPassengers} kursi sesuai jumlah penumpang`
            });
            return;
        }

        const formData = {
            customer_id: customerId,
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
            const response = await axios.post('/admin/seat-booking/bookings', formData);

            if (response.data.success) {
                toast.dismiss(loadingToast);
                setSuccess(true);

                toast.success('Booking berhasil dibuat!', {
                    description: `Kode: ${response.data.booking.booking_code} • Kursi: ${selectedSeats.join(', ')}`,
                    duration: 5000,
                });

                // Reset form after 2 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        } catch (error: any) {
            toast.dismiss(loadingToast);
            console.error('Booking failed:', error);

            const errorMessage = error.response?.data?.error || error.response?.data?.message || error.message;
            const errorDetails = error.response?.data?.errors;

            let description = errorMessage;
            if (errorDetails) {
                const firstError = Object.values(errorDetails)[0];
                description = Array.isArray(firstError) ? firstError[0] : firstError;
            }

            toast.error('Gagal membuat booking', {
                description: description,
                duration: 5000,
            });
        } finally {
            setSubmitting(false);
        }
    };

    return (
        <Card className="border-0 shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 overflow-hidden">
            <div className="bg-gradient-to-r from-green-600 to-green-700 p-6 text-white">
                <CardTitle className="text-xl">Form Booking</CardTitle>
                <CardDescription className="text-green-100 mt-1">Isi detail booking dan pilih kursi</CardDescription>
            </div>
            <CardContent className="p-6">
                <form onSubmit={handleSubmit} className="space-y-6">
                    {/* Customer */}
                    <div className="space-y-2">
                        <Label htmlFor="customer">Customer *</Label>
                        <CustomerSearch onCustomerSelect={setCustomerId} />
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
                            {selectedSeats.length > 0 && (
                                <span className="ml-2 text-green-600 dark:text-green-400">
                                    ✓ Sinkron
                                </span>
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
                                <p className="text-xs text-green-600 dark:text-green-400 font-medium">
                                    🎉 Hemat Rp {priceDiscount.toLocaleString('id-ID')}!
                                </p>
                            )}
                            <p className="text-xs text-muted-foreground">
                                Harga otomatis dari menu Harga (Prices)
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
                            Lokasi Penjemputan
                        </Label>
                        <Input
                            id="pickup_location"
                            type="text"
                            value={pickupLocation}
                            onChange={(e) => setPickupLocation(e.target.value)}
                            placeholder="Alamat penjemputan (opsional)"
                        />
                    </div>

                    {/* Notes */}
                    <div className="space-y-2">
                        <Label htmlFor="notes">Catatan</Label>
                        <Textarea
                            id="notes"
                            value={notes}
                            onChange={(e) => setNotes(e.target.value)}
                            placeholder="Catatan tambahan (opsional)"
                            rows={3}
                        />
                    </div>

                    {/* Submit Button */}
                    <Button
                        type="submit"
                        className="w-full"
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
                            'Buat Booking'
                        )}
                    </Button>
                </form>
            </CardContent>
        </Card>
    );
}
