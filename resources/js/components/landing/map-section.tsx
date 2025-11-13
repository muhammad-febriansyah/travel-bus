import { motion } from 'framer-motion';
import { MapPin } from 'lucide-react';

interface MapSectionProps {
    setting?: {
        site_name: string;
        address: string;
        google_maps_embed?: string;
    };
}

export default function MapSection({ setting }: MapSectionProps) {
    if (!setting?.google_maps_embed) return null;

    return (
        <section id="map" className="relative overflow-hidden bg-white py-20 lg:py-32">
            <div className="container relative z-10 mx-auto px-4">
                {/* Section Header */}
                <motion.div
                    className="mx-auto mb-16 max-w-3xl text-center"
                    initial={{ opacity: 0, y: -30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.6 }}
                >
                    <span className="text-sm font-semibold uppercase tracking-wider text-[#2547F9]">
                        Lokasi Kami
                    </span>
                    <h2 className="mb-6 mt-4 text-3xl font-bold text-gray-900 lg:text-5xl">
                        Kunjungi Kantor Kami
                    </h2>
                    <p className="text-lg text-gray-600">
                        Temukan lokasi kantor kami dan rencanakan kunjungan Anda
                    </p>
                </motion.div>

                {/* Map Container */}
                <motion.div
                    className="mx-auto max-w-6xl"
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.6, delay: 0.2 }}
                >
                    <div className="overflow-hidden rounded-3xl bg-white shadow-2xl">
                        {/* Address Bar */}
                        <div className="bg-gradient-to-r from-[#2547F9] to-indigo-600 p-6">
                            <div className="flex items-center gap-4 text-white">
                                <div className="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-white/20 backdrop-blur">
                                    <MapPin className="h-6 w-6" />
                                </div>
                                <div className="flex-1">
                                    <div className="mb-1 text-sm font-medium text-white/80">
                                        {setting.site_name}
                                    </div>
                                    <div className="text-lg font-bold text-white">
                                        {setting.address}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Google Maps Embed */}
                        <div className="relative h-[400px] lg:h-[500px]">
                            <iframe
                                src={setting.google_maps_embed}
                                className="h-full w-full"
                                style={{ border: 0 }}
                                allowFullScreen
                                loading="lazy"
                                referrerPolicy="no-referrer-when-downgrade"
                                title={`Map of ${setting.site_name}`}
                            />
                        </div>
                    </div>
                </motion.div>
            </div>
        </section>
    );
}
