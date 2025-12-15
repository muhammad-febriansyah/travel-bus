import { useEffect, useState } from 'react';
import { cn } from '@/lib/utils';
import { Badge } from '@/components/ui/badge';
import { Armchair, Loader2, User } from 'lucide-react';
import { toast } from 'sonner';
import axios from 'axios';

interface SeatLayout {
    grid: Array<{
        row: number;
        seats: (string | null)[];
    }>;
    rows: number;
    columns: number;
    aisle_positions: number[];
}

interface Armada {
    id: number;
    name: string;
    capacity: number;
    seatLayout?: {
        seat_map_config: SeatLayout;
    };
}

interface Props {
    armada: Armada;
    travelDate: string;
    travelTime: string;
    selectedSeats: string[];
    onSeatSelect: (seats: string[]) => void;
    maxSeats?: number;
    apiEndpoint?: string; // Allow custom API endpoint
}

export function SeatMap({
    armada,
    travelDate,
    travelTime,
    selectedSeats,
    onSeatSelect,
    maxSeats = 10,
    apiEndpoint = '/admin/seat-booking/availability', // Default to admin endpoint
}: Props) {
    const [occupiedSeats, setOccupiedSeats] = useState<string[]>([]);
    const [loading, setLoading] = useState(false);
    const [seatLayout, setSeatLayout] = useState<SeatLayout | null>(null);

    useEffect(() => {
        if (!armada.id || !travelDate) return;

        fetchSeatAvailability();
    }, [armada.id, travelDate, travelTime]);

    const fetchSeatAvailability = async () => {
        setLoading(true);
        try {
            const params = {
                armada_id: armada.id,
                travel_date: travelDate,
                travel_time: travelTime || null, // Convert empty string to null
            };

            console.log('=== SEAT AVAILABILITY REQUEST ===');
            console.log('Endpoint:', apiEndpoint);
            console.log('Armada ID:', params.armada_id);
            console.log('Travel Date:', params.travel_date);
            console.log('Travel Time:', params.travel_time);
            console.log('================================');

            const response = await axios.get(apiEndpoint, { params });

            console.log('Occupied seats received:', response.data.occupiedSeats);

            setSeatLayout(response.data.seatLayout);
            setOccupiedSeats(response.data.occupiedSeats || []);
        } catch (error) {
            console.error('Failed to fetch seat availability:', error);
            toast.error('Gagal memuat data kursi', {
                description: 'Silakan refresh halaman',
            });
        } finally {
            setLoading(false);
        }
    };

    const handleSeatClick = (seatNumber: string) => {
        console.log('Seat clicked:', seatNumber);

        // Can't select occupied seats
        if (occupiedSeats.includes(seatNumber)) {
            toast.error('Kursi Tidak Tersedia', {
                description: `Kursi ${seatNumber} sudah dipesan`,
                duration: 2000,
            });
            return;
        }

        const isSelected = selectedSeats.includes(seatNumber);

        if (isSelected) {
            // Deselect seat
            const newSeats = selectedSeats.filter(s => s !== seatNumber);
            onSeatSelect(newSeats);
        } else {
            // Select seat
            if (selectedSeats.length >= maxSeats) {
                // Auto-replace: remove first seat and add new one
                const newSeats = [...selectedSeats.slice(1), seatNumber];
                onSeatSelect(newSeats);
            } else {
                // Add to selection
                onSeatSelect([...selectedSeats, seatNumber]);
            }
        }
    };

    const getSeatColor = (seatNumber: string) => {
        if (occupiedSeats.includes(seatNumber)) {
            return 'bg-red-500 border-red-600 cursor-not-allowed opacity-75';
        }
        if (selectedSeats.includes(seatNumber)) {
            return 'bg-blue-500 border-blue-600 hover:bg-blue-600 cursor-pointer';
        }
        return 'bg-green-500 border-green-600 hover:bg-green-600 cursor-pointer';
    };

    if (loading) {
        return (
            <div className="flex items-center justify-center py-12">
                <Loader2 className="h-8 w-8 animate-spin text-muted-foreground" />
            </div>
        );
    }

    if (!seatLayout?.grid) {
        return (
            <div className="text-center py-12 text-muted-foreground">
                Seat layout tidak tersedia
            </div>
        );
    }

    return (
        <div className="space-y-6">
            {/* Legend */}
            <div className="flex flex-wrap gap-3 text-sm">
                <div className="flex items-center gap-2">
                    <div className="w-6 h-6 rounded bg-green-500 border-2 border-green-600" />
                    <span className="text-muted-foreground">Tersedia</span>
                </div>
                <div className="flex items-center gap-2">
                    <div className="w-6 h-6 rounded bg-blue-500 border-2 border-blue-600" />
                    <span className="text-muted-foreground">Dipilih</span>
                </div>
                <div className="flex items-center gap-2">
                    <div className="w-6 h-6 rounded bg-red-500 border-2 border-red-600" />
                    <span className="text-muted-foreground">Terisi</span>
                </div>
            </div>

            {/* Driver */}
            <div className="flex justify-end">
                <Badge variant="secondary" className="gap-2">
                    <User className="h-3 w-3" />
                    Supir
                </Badge>
            </div>

            {/* Seat Grid */}
            <div className="space-y-3">
                {seatLayout.grid.map((row, rowIndex) => (
                    <div key={rowIndex} className="flex justify-center gap-2">
                        {row.seats.map((seat, seatIndex) => (
                            seat === null ? (
                                // Aisle/Empty space
                                <div key={seatIndex} className="w-14 h-14" />
                            ) : (
                                // Seat Button
                                <button
                                    key={seatIndex}
                                    type="button"
                                    onClick={() => handleSeatClick(seat)}
                                    disabled={occupiedSeats.includes(seat)}
                                    className={cn(
                                        'w-14 h-14 rounded-lg border-2 flex items-center justify-center text-white font-bold text-sm transition-all duration-200',
                                        'active:scale-95 hover:scale-105',
                                        getSeatColor(seat)
                                    )}
                                >
                                    {seat}
                                </button>
                            )
                        ))}
                    </div>
                ))}
            </div>

            {/* Selected Seats Summary */}
            {selectedSeats.length > 0 && (
                <div className="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div className="flex items-start gap-3">
                        <Armchair className="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                        <div className="flex-1">
                            <div className="font-semibold text-blue-900 dark:text-blue-100 mb-1">
                                Kursi yang Dipilih:
                            </div>
                            <div className="text-blue-700 dark:text-blue-300 font-mono text-sm">
                                {selectedSeats.sort().join(', ')}
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
