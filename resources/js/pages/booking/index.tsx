import { Head } from '@inertiajs/react';
import { useState } from 'react';
import HomeLayout from '@/layouts/home-layout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { SeatMap } from '../admin/seat-booking/components/seat-map';
import { CustomerBookingForm } from './components/customer-booking-form';
import { Armchair } from 'lucide-react';

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

interface Setting {
    site_name: string;
    logo: string | null;
    phone: string;
    email: string;
}

interface Props {
    setting?: Setting;
    routes: Route[];
    armadas: Armada[];
    categories: Category[];
}

export default function BookingIndex({ setting, routes, armadas, categories }: Props) {
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
        <HomeLayout setting={setting}>
            <Head title={`Booking Travel - ${setting?.site_name || 'Travel'}`} />

            <div className="container mx-auto py-8 px-4 space-y-8 pt-32">
                {/* Main Content */}
                <div className="grid gap-8 lg:grid-cols-2">
                    {/* Left: Booking Form */}
                    <div className="space-y-6">
                        <CustomerBookingForm
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
                        <Card className="border-0 shadow-xl overflow-hidden">
                            <div className="bg-gradient-to-r from-green-600 to-green-700 p-6 text-white">
                                <CardTitle className="text-xl">Pilih Kursi</CardTitle>
                                <CardDescription className="text-green-100 mt-1">
                                    {selectedArmada
                                        ? `Pilih kursi untuk ${selectedArmada.name}`
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
                                        apiEndpoint="/booking/availability"
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
        </HomeLayout>
    );
}
