import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import HomeLayout from '@/layouts/home-layout';
import { Head, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Search, Ticket } from 'lucide-react';
import { FormEvent, useState } from 'react';

interface Props {
    setting: {
        company_name: string;
        logo: string | null;
    };
    errors?: {
        booking_code?: string;
    };
}

export default function CekBooking({ setting, errors }: Props) {
    const [bookingCode, setBookingCode] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        setIsLoading(true);

        router.post(
            '/cek-booking/search',
            { booking_code: bookingCode },
            {
                onFinish: () => setIsLoading(false),
            },
        );
    };

    return (
        <HomeLayout setting={setting}>
            <Head title="Cek Booking" />

            <div className="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 pt-36 pb-20 sm:pt-40 lg:pt-48">
                <div className="container mx-auto px-4">
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.5 }}
                        className="mx-auto max-w-md"
                    >
                        <div className="mb-6 text-center">
                            <div className="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#2547F9]/10">
                                <Ticket className="h-8 w-8 text-[#2547F9]" />
                            </div>
                            <h1 className="mb-3 text-3xl leading-tight font-bold text-gray-900">
                                Cek Status Booking
                            </h1>
                            <p className="text-base leading-relaxed text-gray-600">
                                Masukkan kode booking Anda untuk melihat detail
                                pemesanan
                            </p>
                        </div>

                        <Card className="border-0 shadow-xl">
                            <CardContent>
                                <form
                                    onSubmit={handleSubmit}
                                    className="space-y-4"
                                >
                                    <div className="space-y-2">
                                        <Label htmlFor="booking_code">
                                            Kode Booking
                                        </Label>
                                        <div className="relative">
                                            <Search className="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                            <Input
                                                id="booking_code"
                                                name="booking_code"
                                                type="text"
                                                placeholder="BK-XXXXXXXX"
                                                value={bookingCode}
                                                onChange={(e) =>
                                                    setBookingCode(
                                                        e.target.value.toUpperCase(),
                                                    )
                                                }
                                                className="pl-10"
                                                required
                                            />
                                        </div>
                                        {errors?.booking_code && (
                                            <p className="text-sm text-red-600">
                                                {errors.booking_code}
                                            </p>
                                        )}
                                    </div>

                                    <Button
                                        type="submit"
                                        className="w-full bg-[#2547F9] hover:bg-[#1d3acc]"
                                        disabled={isLoading}
                                    >
                                        {isLoading ? (
                                            <>
                                                <span className="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                                                Mencari...
                                            </>
                                        ) : (
                                            <>
                                                <Search className="mr-2 h-4 w-4" />
                                                Cek Booking
                                            </>
                                        )}
                                    </Button>
                                </form>

                                <div className="mt-6 rounded-lg bg-blue-50 p-4">
                                    <p className="text-sm text-gray-700">
                                        <strong>Contoh kode booking:</strong>{' '}
                                        BK-ABC12345
                                    </p>
                                    <p className="mt-2 text-xs text-gray-600">
                                        Jika Anda tidak menemukan kode booking,
                                        silakan hubungi customer service kami.
                                    </p>
                                </div>
                            </CardContent>
                        </Card>
                    </motion.div>
                </div>
            </div>
        </HomeLayout>
    );
}
