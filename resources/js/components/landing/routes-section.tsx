import { useState } from 'react';
import { ArrowRight, Clock, MapPin, Navigation } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { motion } from 'framer-motion';
import RouteDetailModal from './route-detail-modal';
import RouteBookingModal from './route-booking-modal';

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

interface RoutesSectionProps {
    routes: Route[];
    whatsappNumber?: string;
}

export default function RoutesSection({ routes, whatsappNumber }: RoutesSectionProps) {
    const [selectedRoute, setSelectedRoute] = useState<Route | null>(null);
    const [isDetailModalOpen, setIsDetailModalOpen] = useState(false);
    const [isBookingModalOpen, setIsBookingModalOpen] = useState(false);

    const handleViewDetail = (route: Route) => {
        setSelectedRoute(route);
        setIsDetailModalOpen(true);
    };

    const handleWhatsApp = () => {
        if (!selectedRoute) return;

        const priceList = selectedRoute.prices
            .map(p => `- ${p.category}: ${formatPrice(p.price)}`)
            .join('\n');

        const message = `Halo, saya ingin menanyakan tentang rute:

*Detail Rute:*
- Kode: ${selectedRoute.route_code}
- Dari: ${selectedRoute.origin}
- Ke: ${selectedRoute.destination}
- Jarak: ${selectedRoute.distance} KM
- Estimasi: ${formatDuration(selectedRoute.estimated_duration)}

*Harga:*
${priceList}

Mohon informasi lebih lanjut untuk pemesanan. Terima kasih!`;

        const encodedMessage = encodeURIComponent(message);
        const waNumber = whatsappNumber || '6281234567890';
        window.open(`https://wa.me/${waNumber}?text=${encodedMessage}`, '_blank');
    };

    const handleOpenBookingForm = () => {
        setIsDetailModalOpen(false);
        setIsBookingModalOpen(true);
    };

    const closeDetailModal = () => {
        setIsDetailModalOpen(false);
        setSelectedRoute(null);
    };

    const closeBookingModal = () => {
        setIsBookingModalOpen(false);
        setSelectedRoute(null);
    };
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
        return `${hours}j ${mins > 0 ? `${mins}m` : ''}`;
    };

    const containerVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                staggerChildren: 0.1,
            },
        },
    };

    const cardVariants = {
        hidden: { opacity: 0, y: 20 },
        visible: {
            opacity: 1,
            y: 0,
            transition: { duration: 0.4, ease: 'easeOut' },
        },
    };

    return (
        <section id="routes" className="bg-gray-50 py-20 lg:py-32">
            <div className="container mx-auto px-4">
                {/* Section Header */}
                <motion.div
                    className="mx-auto mb-16 max-w-3xl text-center"
                    initial={{ opacity: 0, y: -30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, amount: 0.3 }}
                    transition={{ duration: 0.6 }}
                >
                    <span className="text-sm font-semibold uppercase tracking-wider text-[#2547F9]">
                        Rute Tersedia
                    </span>
                    <h2 className="mb-6 mt-4 text-3xl font-bold text-gray-900 lg:text-5xl">
                        Pilih Rute Perjalanan Anda
                    </h2>
                    <p className="text-lg text-gray-600">
                        Tersedia berbagai rute antar kota dengan harga terjangkau
                        dan armada berkualitas
                    </p>
                </motion.div>

                {/* Routes Grid */}
                <motion.div
                    className="grid gap-6 md:grid-cols-2 lg:grid-cols-3"
                    variants={containerVariants}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true, amount: 0.1 }}
                >
                    {routes.map((route, index) => (
                        <motion.div
                            key={route.id}
                            className="group overflow-hidden rounded-2xl border border-gray-200 bg-white transition-all duration-300 hover:border-[#2547F9]/50 hover:shadow-xl"
                            variants={cardVariants}
                            whileHover={{ y: -4, scale: 1.02 }}
                            transition={{ duration: 0.3 }}
                        >
                            {/* Header */}
                            <div className="bg-gradient-to-br from-[#2547F9] to-indigo-600 p-6 text-white">
                                <div className="mb-4 flex items-center justify-between">
                                    <span className="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold backdrop-blur-sm">
                                        {route.route_code}
                                    </span>
                                    <Navigation className="h-5 w-5 opacity-80" />
                                </div>

                                <div className="space-y-3">
                                    <div className="flex items-center space-x-3">
                                        <div className="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                                            <MapPin className="h-4 w-4" />
                                        </div>
                                        <div>
                                            <div className="text-xs opacity-80">
                                                Dari
                                            </div>
                                            <div className="text-lg font-bold">
                                                {route.origin}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="flex items-center justify-center">
                                        <ArrowRight className="h-6 w-6 opacity-60" />
                                    </div>

                                    <div className="flex items-center space-x-3">
                                        <div className="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                                            <MapPin className="h-4 w-4" />
                                        </div>
                                        <div>
                                            <div className="text-xs opacity-80">
                                                Ke
                                            </div>
                                            <div className="text-lg font-bold">
                                                {route.destination}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Body */}
                            <div className="space-y-4 p-6">
                                {/* Info */}
                                <div className="flex items-center justify-between text-sm">
                                    <div className="flex items-center space-x-2 text-gray-600">
                                        <Navigation className="h-4 w-4" />
                                        <span>{route.distance} KM</span>
                                    </div>
                                    <div className="flex items-center space-x-2 text-gray-600">
                                        <Clock className="h-4 w-4" />
                                        <span>
                                            ~
                                            {formatDuration(
                                                route.estimated_duration,
                                            )}
                                        </span>
                                    </div>
                                </div>

                                {/* Prices */}
                                <div className="space-y-2 border-t border-gray-200 py-4">
                                    {route.prices.map((price, priceIndex) => (
                                        <div
                                            key={priceIndex}
                                            className="flex items-center justify-between"
                                        >
                                            <span className="text-sm text-gray-600">
                                                {price.category}
                                            </span>
                                            <span className="font-bold text-[#2547F9]">
                                                {formatPrice(price.price)}
                                            </span>
                                        </div>
                                    ))}
                                </div>

                                {/* CTA */}
                                <Button
                                    className="w-full bg-[#2547F9] text-white hover:bg-[#1d3acc] transition-all"
                                    onClick={() => handleViewDetail(route)}
                                >
                                    Lihat Detail
                                </Button>
                            </div>
                        </motion.div>
                    ))}
                </motion.div>

                {/* Detail Modal */}
                {selectedRoute && (
                    <RouteDetailModal
                        isOpen={isDetailModalOpen}
                        onClose={closeDetailModal}
                        route={selectedRoute}
                        onWhatsApp={handleWhatsApp}
                        onBookingForm={handleOpenBookingForm}
                    />
                )}

                {/* Booking Modal */}
                {selectedRoute && (
                    <RouteBookingModal
                        isOpen={isBookingModalOpen}
                        onClose={closeBookingModal}
                        route={selectedRoute}
                        whatsappNumber={whatsappNumber}
                    />
                )}

            </div>
        </section>
    );
}
