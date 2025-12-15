import { Head } from '@inertiajs/react';
import { useState } from 'react';
import StandaloneLayout from '@/layouts/standalone-layout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { SeatMap } from './components/seat-map';
import { BookingForm } from './components/booking-form';
import { Armchair, Calendar, Users } from 'lucide-react';

interface Route {
    id: number;
    origin: string;
    destination: string;
}

interface Category {
    id: number;
    name: string;
}

interface SeatLayout {
    id: number;
    capacity: number;
    layout_type: string;
    seat_map_config: {
        grid: Array<{
            row: number;
            seats: (string | null)[];
        }>;
        rows: number;
        columns: number;
        aisle_positions: number[];
    };
}

interface Armada {
    id: number;
    name: string;
    capacity: number;
    seat_layout_id: number | null;
    category_id: number;
    plate_number: string;
    seatLayout?: SeatLayout;
    category?: Category;
}

interface Props {
    routes: Route[];
    armadas: Armada[];
    categories: Category[];
    seatLayouts: SeatLayout[];
}

export default function SeatBookingIndex({ routes, armadas, categories }: Props) {
    const [selectedArmada, setSelectedArmada] = useState<Armada | null>(null);
    const [selectedSeats, setSelectedSeats] = useState<string[]>([]);
    const [travelDate, setTravelDate] = useState<string>('');
    const [travelTime, setTravelTime] = useState<string>('');
    const [totalPassengers, setTotalPassengers] = useState<number>(1);

    // Auto update total passengers when seats are selected
    const handleSeatSelect = (seats: string[]) => {
        setSelectedSeats(seats);
        if (seats.length > 0) {
            setTotalPassengers(seats.length);
        }
    };

    return (
        <StandaloneLayout>
            <Head title="Seat Booking - Travel Bisnis" />

            <div className="container mx-auto py-8 px-4 space-y-8">
                {/* Hero Header with Gradient */}
                <div className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 p-8 text-white shadow-xl shadow-blue-500/20">
                    <div className="absolute right-0 top-0 h-64 w-64 rounded-full bg-white/10 blur-3xl" />
                    <div className="absolute -bottom-8 -left-8 h-64 w-64 rounded-full bg-white/10 blur-3xl" />

                    <div className="relative z-10 space-y-3">
                        <div className="flex items-center gap-2">
                            <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-white/20 backdrop-blur-sm">
                                <Armchair className="h-6 w-6" />
                            </div>
                            <h1 className="text-3xl font-bold tracking-tight">Pilih Kursi Perjalanan Anda</h1>
                        </div>
                        <p className="text-blue-100 max-w-2xl">
                            Pilih posisi kursi favorit Anda dengan mudah. Sistem pemilihan kursi visual yang praktis dan real-time.
                        </p>
                    </div>
                </div>

                {/* Stats Cards with Modern Design */}
                <div className="grid gap-6 md:grid-cols-3">
                    <Card className="border-0 shadow-lg shadow-slate-200/50 dark:shadow-slate-900/50 hover:shadow-xl transition-all duration-300 bg-gradient-to-br from-white to-slate-50 dark:from-slate-900 dark:to-slate-950">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-sm font-medium text-muted-foreground">Total Armada</CardTitle>
                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-950">
                                <Armchair className="h-5 w-5 text-blue-600 dark:text-blue-400" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                                {armadas.length}
                            </div>
                            <p className="text-xs text-muted-foreground mt-1">
                                {armadas.filter(a => a.seatLayout).length} dengan seat layout
                            </p>
                        </CardContent>
                    </Card>

                    <Card className="border-0 shadow-lg shadow-slate-200/50 dark:shadow-slate-900/50 hover:shadow-xl transition-all duration-300 bg-gradient-to-br from-white to-slate-50 dark:from-slate-900 dark:to-slate-950">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-sm font-medium text-muted-foreground">Total Routes</CardTitle>
                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-950">
                                <Calendar className="h-5 w-5 text-green-600 dark:text-green-400" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-bold bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent">
                                {routes.length}
                            </div>
                            <p className="text-xs text-muted-foreground mt-1">Rute yang tersedia</p>
                        </CardContent>
                    </Card>

                    <Card className="border-0 shadow-lg shadow-slate-200/50 dark:shadow-slate-900/50 hover:shadow-xl transition-all duration-300 bg-gradient-to-br from-white to-slate-50 dark:from-slate-900 dark:to-slate-950">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-sm font-medium text-muted-foreground">Kursi Dipilih</CardTitle>
                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-950">
                                <Users className="h-5 w-5 text-purple-600 dark:text-purple-400" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">
                                {selectedSeats.length}
                            </div>
                            <p className="text-xs text-muted-foreground mt-1">
                                {selectedSeats.length > 0 ? selectedSeats.join(', ') : 'Belum ada kursi dipilih'}
                            </p>
                        </CardContent>
                    </Card>
                </div>

                {/* Main Content */}
                <div className="grid gap-8 lg:grid-cols-2">
                    {/* Left: Booking Form */}
                    <div className="space-y-6">
                        <BookingForm
                            routes={routes}
                            armadas={armadas}
                            categories={categories}
                            selectedSeats={selectedSeats}
                            totalPassengers={totalPassengers}
                            onTotalPassengersChange={setTotalPassengers}
                            onArmadaChange={(armada) => {
                                setSelectedArmada(armada);
                                setSelectedSeats([]);
                                setTotalPassengers(1);
                            }}
                            onDateChange={setTravelDate}
                            onTimeChange={setTravelTime}
                            travelDate={travelDate}
                            travelTime={travelTime}
                        />
                    </div>

                    {/* Right: Seat Map */}
                    <div className="lg:sticky lg:top-24 lg:self-start">
                        <Card className="border-0 shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 overflow-hidden">
                            <div className="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                                <CardTitle className="text-xl">Pilih Kursi</CardTitle>
                                <CardDescription className="text-blue-100 mt-1">
                                    {selectedArmada
                                        ? `Visual seat selection untuk ${selectedArmada.name}`
                                        : 'Pilih armada dan tanggal terlebih dahulu'}
                                </CardDescription>
                            </div>
                            <CardContent className="p-6">
                                {selectedArmada && travelDate ? (
                                    <SeatMap
                                        armada={selectedArmada}
                                        travelDate={travelDate}
                                        travelTime={travelTime}
                                        selectedSeats={selectedSeats}
                                        onSeatSelect={handleSeatSelect}
                                        maxSeats={totalPassengers}
                                    />
                                ) : (
                                    <div className="flex items-center justify-center py-12 text-center text-muted-foreground">
                                        <div className="space-y-2">
                                            <Armchair className="h-12 w-12 mx-auto opacity-20" />
                                            <p>Pilih armada dan tanggal untuk melihat seat map</p>
                                        </div>
                                    </div>
                                )}
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </StandaloneLayout>
    );
}
