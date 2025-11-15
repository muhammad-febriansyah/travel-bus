import { Head } from '@inertiajs/react';
import HomeLayout from '@/layouts/home-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Calendar,
    Clock,
    MapPin,
    User,
    Phone,
    Mail,
    Truck,
    Users,
    CreditCard,
    FileText,
    Download,
    CheckCircle,
    XCircle,
    AlertCircle,
} from 'lucide-react';
import { motion } from 'framer-motion';

interface Props {
    setting: {
        company_name: string;
        logo: string | null;
    };
    booking: {
        id: number;
        booking_code: string;
        status: string;
        customer: {
            name: string;
            phone: string;
            email: string | null;
        };
        route: {
            origin: string;
            destination: string;
            route_code: string;
        };
        armada: {
            name: string;
            plate_number: string;
            vehicle_type: string;
        };
        category: string;
        travel_date: string;
        travel_time: string | null;
        pickup_location: string | null;
        total_passengers: number;
        price_per_person: number;
        total_price: number;
        notes: string | null;
        created_at: string;
    };
}

export default function BookingDetail({ setting, booking }: Props) {
    const getStatusBadge = (status: string) => {
        const badges = {
            pending: {
                icon: AlertCircle,
                text: 'Menunggu Konfirmasi',
                className: 'bg-yellow-100 text-yellow-800 border-yellow-200',
            },
            confirmed: {
                icon: CheckCircle,
                text: 'Dikonfirmasi',
                className: 'bg-blue-100 text-blue-800 border-blue-200',
            },
            completed: {
                icon: CheckCircle,
                text: 'Selesai',
                className: 'bg-green-100 text-green-800 border-green-200',
            },
            cancelled: {
                icon: XCircle,
                text: 'Dibatalkan',
                className: 'bg-red-100 text-red-800 border-red-200',
            },
        };

        const badge = badges[status as keyof typeof badges] || badges.pending;
        const Icon = badge.icon;

        return (
            <div className={`inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold ${badge.className}`}>
                <Icon className="h-4 w-4" />
                {badge.text}
            </div>
        );
    };

    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(price);
    };

    const handleDownloadInvoice = () => {
        window.open(`/booking/${booking.booking_code}/invoice`, '_blank');
    };

    return (
        <HomeLayout setting={setting}>
            <Head title={`Booking ${booking.booking_code}`} />

            <div className="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 pt-32 pb-16 sm:pt-36 lg:pt-40 sm:pb-20">
                <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.5 }}
                        className="mx-auto w-full max-w-5xl space-y-8"
                    >
                        {/* Header */}
                        <div className="space-y-6 rounded-2xl bg-white/70 px-5 py-6 shadow-[0_20px_40px_rgba(15,23,42,0.08)] backdrop-blur-md sm:px-8">
                            <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <h1 className="text-3xl font-bold text-gray-900">Detail Booking</h1>
                                    <p className="text-lg text-gray-600">
                                        Kode: <span className="font-mono font-bold text-[#2547F9]">{booking.booking_code}</span>
                                    </p>
                                </div>
                                <Button
                                    onClick={handleDownloadInvoice}
                                    className="bg-[#2547F9] hover:bg-[#1d3acc]"
                                >
                                    <Download className="mr-2 h-4 w-4" />
                                    Download Invoice
                                </Button>
                            </div>
                            <div>{getStatusBadge(booking.status)}</div>
                        </div>

                        <div className="grid gap-6 lg:grid-cols-2">
                            {/* Customer Info */}
                            <Card className="border-0 shadow-lg rounded-2xl">
                                <CardHeader>
                                    <CardTitle className="flex items-center gap-2">
                                        <User className="h-5 w-5 text-[#2547F9]" />
                                        Data Pemesan
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-3">
                                    <div className="flex items-start gap-3">
                                        <User className="mt-1 h-4 w-4 text-gray-400" />
                                        <div>
                                            <p className="text-sm text-gray-500">Nama</p>
                                            <p className="font-semibold text-gray-900">{booking.customer.name}</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-3">
                                        <Phone className="mt-1 h-4 w-4 text-gray-400" />
                                        <div>
                                            <p className="text-sm text-gray-500">No. Telepon</p>
                                            <p className="font-semibold text-gray-900">{booking.customer.phone}</p>
                                        </div>
                                    </div>
                                    {booking.customer.email && (
                                        <div className="flex items-start gap-3">
                                            <Mail className="mt-1 h-4 w-4 text-gray-400" />
                                            <div>
                                                <p className="text-sm text-gray-500">Email</p>
                                                <p className="font-semibold text-gray-900">{booking.customer.email}</p>
                                            </div>
                                        </div>
                                    )}
                                </CardContent>
                            </Card>

                            {/* Trip Info */}
                            <Card className="border-0 shadow-lg rounded-2xl">
                                <CardHeader>
                                    <CardTitle className="flex items-center gap-2">
                                        <MapPin className="h-5 w-5 text-[#2547F9]" />
                                        Detail Perjalanan
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-3">
                                    <div className="flex items-start gap-3">
                                        <MapPin className="mt-1 h-4 w-4 text-gray-400" />
                                        <div>
                                            <p className="text-sm text-gray-500">Rute</p>
                                            <p className="font-semibold text-gray-900">
                                                {booking.route.origin} → {booking.route.destination}
                                            </p>
                                            <p className="text-xs text-gray-500">{booking.route.route_code}</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-3">
                                        <Calendar className="mt-1 h-4 w-4 text-gray-400" />
                                        <div>
                                            <p className="text-sm text-gray-500">Tanggal Keberangkatan</p>
                                            <p className="font-semibold text-gray-900">{booking.travel_date}</p>
                                        </div>
                                    </div>
                                    {booking.travel_time && (
                                        <div className="flex items-start gap-3">
                                            <Clock className="mt-1 h-4 w-4 text-gray-400" />
                                            <div>
                                                <p className="text-sm text-gray-500">Jam Keberangkatan</p>
                                                <p className="font-semibold text-gray-900">{booking.travel_time} WIB</p>
                                            </div>
                                        </div>
                                    )}
                                    {booking.pickup_location && (
                                        <div className="flex items-start gap-3">
                                            <MapPin className="mt-1 h-4 w-4 text-gray-400" />
                                            <div>
                                                <p className="text-sm text-gray-500">Lokasi Penjemputan</p>
                                                <p className="font-semibold text-gray-900">{booking.pickup_location}</p>
                                            </div>
                                        </div>
                                    )}
                                </CardContent>
                            </Card>

                            {/* Vehicle Info */}
                            <Card className="border-0 shadow-lg rounded-2xl">
                                <CardHeader>
                                    <CardTitle className="flex items-center gap-2">
                                        <Truck className="h-5 w-5 text-[#2547F9]" />
                                        Kendaraan
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-3">
                                    <div className="flex items-start gap-3">
                                        <Truck className="mt-1 h-4 w-4 text-gray-400" />
                                        <div>
                                            <p className="text-sm text-gray-500">Armada</p>
                                            <p className="font-semibold text-gray-900">{booking.armada.name}</p>
                                            <p className="text-xs text-gray-500">{booking.armada.vehicle_type}</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-3">
                                        <FileText className="mt-1 h-4 w-4 text-gray-400" />
                                        <div>
                                            <p className="text-sm text-gray-500">Plat Nomor</p>
                                            <p className="font-semibold text-gray-900">{booking.armada.plate_number}</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-3">
                                        <FileText className="mt-1 h-4 w-4 text-gray-400" />
                                        <div>
                                            <p className="text-sm text-gray-500">Kategori</p>
                                            <p className="font-semibold text-gray-900">{booking.category}</p>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>

                            {/* Payment Info */}
                            <Card className="border-0 shadow-lg rounded-2xl">
                                <CardHeader>
                                    <CardTitle className="flex items-center gap-2">
                                        <CreditCard className="h-5 w-5 text-[#2547F9]" />
                                        Informasi Harga
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-3">
                                    <div className="flex items-start gap-3">
                                        <Users className="mt-1 h-4 w-4 text-gray-400" />
                                        <div>
                                            <p className="text-sm text-gray-500">Jumlah Penumpang</p>
                                            <p className="font-semibold text-gray-900">{booking.total_passengers} orang</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-3">
                                        <CreditCard className="mt-1 h-4 w-4 text-gray-400" />
                                        <div>
                                            <p className="text-sm text-gray-500">Harga per Orang</p>
                                            <p className="font-semibold text-gray-900">{formatPrice(booking.price_per_person)}</p>
                                        </div>
                                    </div>
                                    <div className="border-t pt-3">
                                        <div className="flex items-start gap-3">
                                            <CreditCard className="mt-1 h-5 w-5 text-green-600" />
                                            <div>
                                                <p className="text-sm text-gray-500">Total Harga</p>
                                                <p className="text-2xl font-bold text-green-600">{formatPrice(booking.total_price)}</p>
                                            </div>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>

                        {/* Notes */}
                        {booking.notes && (
                            <Card className="mt-6 border-0 shadow-lg rounded-2xl">
                                <CardHeader>
                                    <CardTitle className="flex items-center gap-2">
                                        <FileText className="h-5 w-5 text-[#2547F9]" />
                                        Catatan
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <p className="text-gray-700">{booking.notes}</p>
                                </CardContent>
                            </Card>
                        )}

                        {/* Info Message */}
                        {booking.status === 'pending' && (
                            <Card className="mt-6 border-yellow-200 bg-yellow-50 shadow-lg rounded-2xl">
                                <CardContent className="pt-6">
                                    <div className="flex gap-3">
                                        <AlertCircle className="h-5 w-5 flex-shrink-0 text-yellow-600" />
                                        <div>
                                            <p className="font-semibold text-yellow-900">Menunggu Konfirmasi</p>
                                            <p className="mt-1 text-sm text-yellow-700">
                                                Booking Anda sedang diproses. Jam keberangkatan dan lokasi penjemputan akan
                                                dikonfirmasi oleh admin kami segera. Anda akan menerima notifikasi setelah
                                                booking dikonfirmasi.
                                            </p>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        )}

                        {booking.status === 'confirmed' && (
                            <Card className="mt-6 border-blue-200 bg-blue-50 shadow-lg rounded-2xl">
                                <CardContent className="pt-6">
                                    <div className="flex gap-3">
                                        <CheckCircle className="h-5 w-5 flex-shrink-0 text-blue-600" />
                                        <div>
                                            <p className="font-semibold text-blue-900">Booking Dikonfirmasi</p>
                                            <p className="mt-1 text-sm text-blue-700">
                                                Booking Anda telah dikonfirmasi! Silakan hadir di lokasi penjemputan sesuai
                                                jadwal yang tertera. Terima kasih telah menggunakan layanan kami.
                                            </p>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        )}

                        <div className="mt-6 text-center">
                            <p className="text-sm text-gray-500">
                                Booking dibuat pada {booking.created_at}
                            </p>
                        </div>
                    </motion.div>
                </div>
            </div>
        </HomeLayout>
    );
}
