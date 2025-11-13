import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { MapPin, Navigation, Clock, ArrowRight, MessageCircle, FileText, DollarSign } from 'lucide-react';

interface RouteDetailModalProps {
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
    onWhatsApp: () => void;
    onBookingForm: () => void;
}

export default function RouteDetailModal({
    isOpen,
    onClose,
    route,
    onWhatsApp,
    onBookingForm,
}: RouteDetailModalProps) {
    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(price);
    };

    const formatDuration = (minutes: number) => {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours} Jam ${mins > 0 ? `${mins} Menit` : ''}`;
    };

    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="max-h-[90vh] overflow-y-auto sm:max-w-[600px]">
                <DialogHeader>
                    <DialogTitle className="text-2xl font-bold text-gray-900">
                        Detail Rute
                    </DialogTitle>
                </DialogHeader>

                <div className="space-y-6">
                    {/* Route Header */}
                    <div className="rounded-xl bg-gradient-to-br from-[#2547F9] to-indigo-600 p-6 text-white">
                        <div className="mb-4 flex items-center justify-between">
                            <span className="rounded-full bg-white/20 px-4 py-1.5 text-sm font-semibold backdrop-blur-sm">
                                {route.route_code}
                            </span>
                            <Navigation className="h-6 w-6 opacity-80" />
                        </div>

                        <div className="space-y-4">
                            {/* Origin */}
                            <div className="flex items-center gap-4">
                                <div className="flex h-12 w-12 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                                    <MapPin className="h-6 w-6" />
                                </div>
                                <div>
                                    <div className="text-sm opacity-80">Titik Keberangkatan</div>
                                    <div className="text-2xl font-bold">{route.origin}</div>
                                </div>
                            </div>

                            {/* Arrow */}
                            <div className="flex justify-center">
                                <ArrowRight className="h-8 w-8 opacity-60" />
                            </div>

                            {/* Destination */}
                            <div className="flex items-center gap-4">
                                <div className="flex h-12 w-12 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                                    <MapPin className="h-6 w-6" />
                                </div>
                                <div>
                                    <div className="text-sm opacity-80">Tujuan</div>
                                    <div className="text-2xl font-bold">{route.destination}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Route Info */}
                    <div className="grid gap-4 rounded-xl border border-gray-200 bg-gray-50 p-6 sm:grid-cols-2">
                        <div className="flex items-center gap-3">
                            <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-[#2547F9]/10">
                                <Navigation className="h-6 w-6 text-[#2547F9]" />
                            </div>
                            <div>
                                <p className="text-sm text-gray-500">Jarak Tempuh</p>
                                <p className="text-lg font-semibold text-gray-900">{route.distance} KM</p>
                            </div>
                        </div>

                        <div className="flex items-center gap-3">
                            <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-[#2547F9]/10">
                                <Clock className="h-6 w-6 text-[#2547F9]" />
                            </div>
                            <div>
                                <p className="text-sm text-gray-500">Estimasi Waktu</p>
                                <p className="text-lg font-semibold text-gray-900">
                                    {formatDuration(route.estimated_duration)}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Pricing */}
                    <div className="space-y-3">
                        <div className="flex items-center gap-2">
                            <DollarSign className="h-5 w-5 text-[#2547F9]" />
                            <h4 className="font-semibold text-gray-900">Harga Per Kategori:</h4>
                        </div>
                        <div className="space-y-2 rounded-xl border border-gray-200 bg-white p-4">
                            {route.prices.map((price, index) => (
                                <div
                                    key={index}
                                    className="flex items-center justify-between rounded-lg bg-gray-50 p-3"
                                >
                                    <span className="font-medium text-gray-700">{price.category}</span>
                                    <span className="text-lg font-bold text-[#2547F9]">
                                        {formatPrice(price.price)}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Additional Info */}
                    <div className="rounded-xl bg-blue-50 p-4">
                        <p className="text-sm text-gray-700">
                            <span className="font-semibold">Catatan:</span> Harga dapat berubah sewaktu-waktu
                            tergantung ketersediaan dan musim. Hubungi kami untuk informasi lebih lanjut dan
                            promo menarik.
                        </p>
                    </div>

                    {/* Close Button */}
                    <div className="border-t pt-6">
                        <Button
                            type="button"
                            onClick={onClose}
                            className="w-full bg-[#2547F9] text-white hover:bg-[#1d3acc]"
                        >
                            Tutup
                        </Button>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    );
}
