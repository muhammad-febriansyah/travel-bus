import { useState } from 'react';
import { Award, Bus, CheckCircle, Users } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { motion } from 'framer-motion';
import ArmadaDetailModal from './armada-detail-modal';
import BookingModal from './booking-modal';

interface Armada {
    id: number;
    name: string;
    vehicle_type: string;
    capacity: number;
    category: string;
    description: string;
    image: string | null;
}

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

interface ArmadaSectionProps {
    armadas: Armada[];
    routes: Route[];
    whatsappNumber?: string;
}

export default function ArmadaSection({ armadas, routes, whatsappNumber }: ArmadaSectionProps) {
    const [selectedArmada, setSelectedArmada] = useState<Armada | null>(null);
    const [isDetailModalOpen, setIsDetailModalOpen] = useState(false);
    const [isBookingModalOpen, setIsBookingModalOpen] = useState(false);
    const [currentPage, setCurrentPage] = useState(1);

    const ITEMS_PER_PAGE = 6;
    const totalPages = Math.ceil(armadas.length / ITEMS_PER_PAGE);
    const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
    const endIndex = startIndex + ITEMS_PER_PAGE;
    const currentArmadas = armadas.slice(startIndex, endIndex);

    const handlePageChange = (page: number) => {
        setCurrentPage(page);
        // Scroll to armada section
        document.getElementById('armada')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    const handleViewDetail = (armada: Armada) => {
        setSelectedArmada(armada);
        setIsDetailModalOpen(true);
    };

    const handleWhatsApp = () => {
        if (!selectedArmada) return;

        const message = `Halo, saya ingin menanyakan tentang armada:

*Detail Armada:*
- Nama: ${selectedArmada.name}
- Tipe: ${selectedArmada.vehicle_type}
- Kapasitas: ${selectedArmada.capacity} orang
- Kategori: ${selectedArmada.category}

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
        setSelectedArmada(null);
    };

    const closeBookingModal = () => {
        setIsBookingModalOpen(false);
        setSelectedArmada(null);
    };
    const containerVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                staggerChildren: 0.15,
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
        <section id="armada" className="bg-white py-20 lg:py-32">
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
                        Armada Kami
                    </span>
                    <h2 className="mb-6 mt-4 text-3xl font-bold text-gray-900 lg:text-5xl">
                        Kendaraan Berkualitas & Terawat
                    </h2>
                    <p className="text-lg text-gray-600">
                        Semua armada kami dalam kondisi prima, terawat, dan siap
                        memberikan kenyamanan maksimal untuk perjalanan Anda
                    </p>
                </motion.div>

                {/* Armada Grid */}
                <motion.div
                    key={currentPage}
                    className="grid gap-8 md:grid-cols-2 lg:grid-cols-3"
                    variants={containerVariants}
                    initial="hidden"
                    animate="visible"
                    viewport={{ once: false, amount: 0.1 }}
                >
                    {currentArmadas.map((armada) => (
                        <motion.div
                            key={armada.id}
                            className="group overflow-hidden rounded-2xl border border-gray-200 bg-white transition-all duration-300 hover:border-[#2547F9]/50 hover:shadow-xl"
                            variants={cardVariants}
                            whileHover={{ y: -4, scale: 1.01 }}
                            transition={{ duration: 0.3, ease: 'easeOut' }}
                        >
                            {/* Image */}
                            <div className="relative h-48 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                                {armada.image ? (
                                    <img
                                        src={armada.image}
                                        alt={armada.name}
                                        className="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    />
                                ) : (
                                    <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#2547F9]/10 to-indigo-500/10">
                                        <Bus className="h-20 w-20 text-[#2547F9]/30" />
                                    </div>
                                )}

                                {/* Category Badge */}
                                <div className="absolute right-4 top-4">
                                    <span className="rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-[#2547F9] shadow-lg backdrop-blur-sm">
                                        {armada.category}
                                    </span>
                                </div>

                                {/* Available Badge */}
                                <div className="absolute bottom-4 left-4">
                                    <div className="flex items-center space-x-2 rounded-full bg-green-500/95 px-3 py-1 text-xs font-semibold text-white shadow-lg backdrop-blur-sm">
                                        <CheckCircle className="h-3 w-3" />
                                        <span>Tersedia</span>
                                    </div>
                                </div>
                            </div>

                            {/* Content */}
                            <div className="space-y-4 p-6">
                                {/* Header */}
                                <div>
                                    <h3 className="mb-2 text-xl font-bold text-gray-900 transition-colors group-hover:text-[#2547F9]">
                                        {armada.name}
                                    </h3>
                                    <p className="line-clamp-2 text-sm text-gray-600">
                                        {armada.description}
                                    </p>
                                </div>

                                {/* Specs */}
                                <div className="flex items-center justify-between border-t border-gray-200 py-3">
                                    <div className="flex items-center space-x-2 text-gray-600">
                                        <Bus className="h-4 w-4" />
                                        <span className="text-sm">
                                            {armada.vehicle_type}
                                        </span>
                                    </div>
                                    <div className="flex items-center space-x-2 text-gray-600">
                                        <Users className="h-4 w-4" />
                                        <span className="text-sm font-semibold">
                                            {armada.capacity} Seat
                                        </span>
                                    </div>
                                </div>

                                {/* Features */}
                                <div className="space-y-2 border-t border-gray-200 py-3">
                                    {[
                                        'AC & Comfortable Seat',
                                        'Driver Profesional',
                                        'Terawat & Bersih',
                                    ].map((feature, index) => (
                                        <div
                                            key={index}
                                            className="flex items-center space-x-2 text-sm text-gray-600"
                                        >
                                            <CheckCircle className="h-4 w-4 text-green-500" />
                                            <span>{feature}</span>
                                        </div>
                                    ))}
                                </div>

                                {/* CTA */}
                                <Button
                                    className="w-full bg-[#2547F9] text-white hover:bg-[#1d3acc] transition-all"
                                    onClick={() => handleViewDetail(armada)}
                                >
                                    Lihat Detail
                                </Button>
                            </div>
                        </motion.div>
                    ))}
                </motion.div>

                {/* Pagination */}
                {totalPages > 1 && (
                    <motion.div
                        className="mt-12 flex flex-wrap items-center justify-center gap-4"
                        initial={{ opacity: 0, y: 20 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        viewport={{ once: true }}
                        transition={{ duration: 0.4 }}
                    >
                        <Button
                            variant="outline"
                            onClick={() => handlePageChange(Math.max(currentPage - 1, 1))}
                            disabled={currentPage === 1}
                            className="border-[#2547F9] text-[#2547F9] hover:bg-[#2547F9] hover:text-white disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            ← Sebelumnya
                        </Button>

                        <div className="flex items-center gap-2">
                            {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
                                <button
                                    key={page}
                                    onClick={() => handlePageChange(page)}
                                    className={`h-10 w-10 rounded-lg font-semibold transition-all ${
                                        currentPage === page
                                            ? 'bg-[#2547F9] text-white shadow-lg'
                                            : 'border border-gray-300 text-gray-600 hover:border-[#2547F9] hover:text-[#2547F9]'
                                    }`}
                                >
                                    {page}
                                </button>
                            ))}
                        </div>

                        <Button
                            variant="outline"
                            onClick={() => handlePageChange(Math.min(currentPage + 1, totalPages))}
                            disabled={currentPage === totalPages}
                            className="border-[#2547F9] text-[#2547F9] hover:bg-[#2547F9] hover:text-white disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Selanjutnya →
                        </Button>
                    </motion.div>
                )}

                {/* Detail Modal */}
                {selectedArmada && (
                    <ArmadaDetailModal
                        isOpen={isDetailModalOpen}
                        onClose={closeDetailModal}
                        armada={selectedArmada}
                        onWhatsApp={handleWhatsApp}
                        onBookingForm={handleOpenBookingForm}
                    />
                )}

                {/* Booking Modal */}
                {selectedArmada && (
                    <BookingModal
                        isOpen={isBookingModalOpen}
                        onClose={closeBookingModal}
                        armada={selectedArmada}
                        routes={routes}
                        whatsappNumber={whatsappNumber}
                    />
                )}

                {/* Bottom Info */}
                <motion.div
                    className="mt-16"
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.6, delay: 0.3 }}
                >
                    <div className="rounded-3xl bg-gradient-to-br from-[#2547F9]/10 to-indigo-500/10 p-8 lg:p-12">
                        <div className="grid gap-8 text-center md:grid-cols-3">
                            {[
                                {
                                    icon: Award,
                                    title: 'Standar Internasional',
                                    description:
                                        'Semua armada memenuhi standar keselamatan',
                                },
                                {
                                    icon: CheckCircle,
                                    title: 'Maintenance Rutin',
                                    description:
                                        'Perawatan berkala untuk performa optimal',
                                },
                                {
                                    icon: Bus,
                                    title: 'Armada Beragam',
                                    description:
                                        'Pilihan kelas ekonomi hingga eksekutif',
                                },
                            ].map((item, index) => {
                                const Icon = item.icon;
                                return (
                                    <motion.div
                                        key={index}
                                        className="space-y-2"
                                        initial={{ opacity: 0, y: 20 }}
                                        whileInView={{ opacity: 1, y: 0 }}
                                        viewport={{ once: true }}
                                        transition={{ delay: index * 0.15, duration: 0.4 }}
                                    >
                                        <div className="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#2547F9]">
                                            <Icon className="h-8 w-8 text-white" />
                                        </div>
                                        <h4 className="text-lg font-bold text-gray-900">
                                            {item.title}
                                        </h4>
                                        <p className="text-gray-600">
                                            {item.description}
                                        </p>
                                    </motion.div>
                                );
                            })}
                        </div>
                    </div>
                </motion.div>
            </div>
        </section>
    );
}
