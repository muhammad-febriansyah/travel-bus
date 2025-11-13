import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Users, Car, CheckCircle, MessageCircle, FileText } from 'lucide-react';

interface ArmadaDetailModalProps {
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
    onWhatsApp: () => void;
    onBookingForm: () => void;
}

export default function ArmadaDetailModal({
    isOpen,
    onClose,
    armada,
    onWhatsApp,
    onBookingForm,
}: ArmadaDetailModalProps) {
    return (
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="max-h-[90vh] overflow-y-auto sm:max-w-[700px]">
                <DialogHeader>
                    <DialogTitle className="text-2xl font-bold text-gray-900">
                        Detail Armada
                    </DialogTitle>
                </DialogHeader>

                {/* Armada Image */}
                {armada.image && (
                    <div className="overflow-hidden rounded-xl">
                        <img
                            src={armada.image}
                            alt={armada.name}
                            className="h-64 w-full object-cover"
                        />
                    </div>
                )}

                {/* Armada Details */}
                <div className="space-y-6">
                    {/* Header */}
                    <div>
                        <div className="mb-2 flex items-center gap-2">
                            <h3 className="text-2xl font-bold text-gray-900">{armada.name}</h3>
                            <span className="rounded-full bg-[#2547F9]/10 px-3 py-1 text-sm font-semibold text-[#2547F9]">
                                {armada.category}
                            </span>
                        </div>
                        <p className="text-gray-600">{armada.description}</p>
                    </div>

                    {/* Specifications */}
                    <div className="grid gap-4 rounded-xl border border-gray-200 bg-gray-50 p-6 sm:grid-cols-2">
                        <div className="flex items-center gap-3">
                            <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-[#2547F9]/10">
                                <Car className="h-6 w-6 text-[#2547F9]" />
                            </div>
                            <div>
                                <p className="text-sm text-gray-500">Tipe Kendaraan</p>
                                <p className="font-semibold text-gray-900">{armada.vehicle_type}</p>
                            </div>
                        </div>

                        <div className="flex items-center gap-3">
                            <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-[#2547F9]/10">
                                <Users className="h-6 w-6 text-[#2547F9]" />
                            </div>
                            <div>
                                <p className="text-sm text-gray-500">Kapasitas</p>
                                <p className="font-semibold text-gray-900">{armada.capacity} Penumpang</p>
                            </div>
                        </div>
                    </div>

                    {/* Features */}
                    <div className="space-y-3">
                        <h4 className="font-semibold text-gray-900">Fasilitas:</h4>
                        <div className="grid gap-3 sm:grid-cols-2">
                            {[
                                'AC & Comfortable Seat',
                                'Driver Profesional',
                                'Terawat & Bersih',
                                'Asuransi Perjalanan',
                                'Audio System',
                                'Safety Standard',
                            ].map((feature, index) => (
                                <div
                                    key={index}
                                    className="flex items-center gap-2 text-sm text-gray-700"
                                >
                                    <CheckCircle className="h-5 w-5 text-green-500" />
                                    <span>{feature}</span>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Action Buttons */}
                    <div className="space-y-3 border-t pt-6">
                        <p className="text-center text-sm text-gray-600">
                            Pilih cara pemesanan yang Anda inginkan:
                        </p>
                        <div className="grid gap-3 sm:grid-cols-2">
                            <Button
                                onClick={onWhatsApp}
                                className="h-auto flex-col gap-2 bg-[#25D366] py-4 text-white hover:bg-[#20BA5A]"
                            >
                                <MessageCircle className="h-6 w-6" />
                                <div className="text-center">
                                    <div className="font-semibold">Chat via WhatsApp</div>
                                    <div className="text-xs opacity-90">Cepat & Mudah</div>
                                </div>
                            </Button>

                            <Button
                                onClick={onBookingForm}
                                className="h-auto flex-col gap-2 bg-[#2547F9] py-4 text-white hover:bg-[#1d3acc]"
                            >
                                <FileText className="h-6 w-6" />
                                <div className="text-center">
                                    <div className="font-semibold">Form Booking</div>
                                    <div className="text-xs opacity-90">Pemesanan Formal</div>
                                </div>
                            </Button>
                        </div>

                        <Button
                            type="button"
                            variant="outline"
                            onClick={onClose}
                            className="w-full border-gray-300"
                        >
                            Tutup
                        </Button>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    );
}
