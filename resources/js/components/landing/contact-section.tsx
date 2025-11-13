import { motion } from 'framer-motion';
import { Mail, Phone, MapPin, Facebook, Instagram, Twitter, Youtube, Linkedin } from 'lucide-react';

interface ContactProps {
    setting?: {
        site_name: string;
        phone: string;
        email: string;
        address: string;
        facebook_url?: string;
        instagram_url?: string;
        twitter_url?: string;
        whatsapp_number?: string;
        youtube_url?: string;
        tiktok_url?: string;
        linkedin_url?: string;
    };
}

export default function ContactSection({ setting }: ContactProps) {
    const containerVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                staggerChildren: 0.2,
            },
        },
    };

    const itemVariants = {
        hidden: { opacity: 0, y: 30 },
        visible: {
            opacity: 1,
            y: 0,
            transition: { duration: 0.6 },
        },
    };

    return (
        <section id="contact" className="relative overflow-hidden bg-gradient-to-br from-gray-50 to-white py-20 lg:py-32">
            {/* Decorative Background */}
            <div className="absolute -left-48 top-0 h-96 w-96 rounded-full bg-[#2547F9]/5 blur-[120px]" />
            <div className="absolute -right-48 bottom-0 h-96 w-96 rounded-full bg-indigo-500/5 blur-[120px]" />

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
                        Hubungi Kami
                    </span>
                    <h2 className="mb-6 mt-4 text-3xl font-bold text-gray-900 lg:text-5xl">
                        Siap Melayani Anda
                    </h2>
                    <p className="text-lg text-gray-600">
                        Tim kami siap membantu Anda 24/7. Hubungi kami melalui channel yang tersedia.
                    </p>
                </motion.div>

                {/* Contact Info Grid */}
                <motion.div
                    className="mx-auto max-w-6xl"
                    variants={containerVariants}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                >
                    <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-16">
                        {/* Phone */}
                        {setting?.phone && (
                            <motion.a
                                href={`tel:${setting.phone}`}
                                className="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg transition-all hover:shadow-2xl"
                                variants={itemVariants}
                                whileHover={{ y: -8 }}
                            >
                                <div className="absolute inset-0 bg-gradient-to-br from-[#2547F9]/5 to-indigo-500/5 opacity-0 transition-opacity group-hover:opacity-100" />
                                <div className="relative flex items-start gap-4">
                                    <div className="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-xl bg-[#2547F9]/10 text-[#2547F9] transition-all group-hover:scale-110 group-hover:bg-[#2547F9] group-hover:text-white">
                                        <Phone className="h-7 w-7" />
                                    </div>
                                    <div className="flex-1 text-left">
                                        <div className="mb-1 text-sm font-medium text-gray-500">
                                            Telepon
                                        </div>
                                        <div className="text-xl font-bold text-gray-900">
                                            {setting.phone}
                                        </div>
                                        <div className="mt-2 text-sm text-gray-600">
                                            Hubungi kami sekarang
                                        </div>
                                    </div>
                                </div>
                            </motion.a>
                        )}

                        {/* WhatsApp */}
                        {setting?.whatsapp_number && (
                            <motion.a
                                href={`https://wa.me/${setting.whatsapp_number}`}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg transition-all hover:shadow-2xl"
                                variants={itemVariants}
                                whileHover={{ y: -8 }}
                            >
                                <div className="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 opacity-0 transition-opacity group-hover:opacity-100" />
                                <div className="relative flex items-start gap-4">
                                    <div className="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-600 transition-all group-hover:scale-110 group-hover:bg-[#25D366] group-hover:text-white">
                                        <svg className="h-7 w-7" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                    </div>
                                    <div className="flex-1 text-left">
                                        <div className="mb-1 text-sm font-medium text-gray-500">
                                            WhatsApp
                                        </div>
                                        <div className="text-xl font-bold text-gray-900">
                                            +{setting.whatsapp_number}
                                        </div>
                                        <div className="mt-2 text-sm text-gray-600">
                                            Chat dengan kami
                                        </div>
                                    </div>
                                </div>
                            </motion.a>
                        )}

                        {/* Email */}
                        {setting?.email && (
                            <motion.a
                                href={`mailto:${setting.email}`}
                                className="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg transition-all hover:shadow-2xl"
                                variants={itemVariants}
                                whileHover={{ y: -8 }}
                            >
                                <div className="absolute inset-0 bg-gradient-to-br from-[#2547F9]/5 to-indigo-500/5 opacity-0 transition-opacity group-hover:opacity-100" />
                                <div className="relative flex items-start gap-4">
                                    <div className="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-xl bg-[#2547F9]/10 text-[#2547F9] transition-all group-hover:scale-110 group-hover:bg-[#2547F9] group-hover:text-white">
                                        <Mail className="h-7 w-7" />
                                    </div>
                                    <div className="flex-1 text-left">
                                        <div className="mb-1 text-sm font-medium text-gray-500">
                                            Email
                                        </div>
                                        <div className="text-xl font-bold text-gray-900 break-all">
                                            {setting.email}
                                        </div>
                                        <div className="mt-2 text-sm text-gray-600">
                                            Kirim email kepada kami
                                        </div>
                                    </div>
                                </div>
                            </motion.a>
                        )}
                    </div>

                    {/* Address Card */}
                    {setting?.address && (
                        <motion.div
                            className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#2547F9] to-indigo-600 p-8 shadow-2xl"
                            variants={itemVariants}
                            whileInView={{ opacity: 1, y: 0 }}
                        >
                            <div className="relative flex items-start gap-4 text-white">
                                <div className="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-xl bg-white/20 backdrop-blur">
                                    <MapPin className="h-7 w-7" />
                                </div>
                                <div className="flex-1 text-left">
                                    <div className="mb-1 text-sm font-medium text-white/80">
                                        Alamat Kantor
                                    </div>
                                    <div className="text-xl font-bold text-white">
                                        {setting.address}
                                    </div>
                                    <div className="mt-2 text-sm text-white/80">
                                        Kunjungi kantor kami untuk informasi lebih lanjut
                                    </div>
                                </div>
                            </div>
                            {/* Decorative circles */}
                            <div className="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10" />
                            <div className="absolute -bottom-8 -left-8 h-40 w-40 rounded-full bg-white/10" />
                        </motion.div>
                    )}

                    {/* Social Media */}
                    <motion.div
                        className="mt-16 text-center"
                        variants={itemVariants}
                    >
                        <h4 className="mb-2 text-2xl font-bold text-gray-900">
                            Ikuti Kami
                        </h4>
                        <p className="mb-8 text-gray-600">
                            Dapatkan update terbaru dari kami
                        </p>
                        <div className="flex flex-wrap justify-center gap-3">
                            {setting?.facebook_url && (
                                <motion.a
                                    href={setting.facebook_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="group flex h-12 w-12 items-center justify-center rounded-xl bg-white text-gray-600 shadow-md transition-all hover:shadow-lg"
                                    whileHover={{ scale: 1.1, y: -3 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    <Facebook className="h-5 w-5 transition-colors group-hover:text-[#1877F2]" />
                                </motion.a>
                            )}
                            {setting?.instagram_url && (
                                <motion.a
                                    href={setting.instagram_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="group flex h-12 w-12 items-center justify-center rounded-xl bg-white text-gray-600 shadow-md transition-all hover:shadow-lg"
                                    whileHover={{ scale: 1.1, y: -3 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    <Instagram className="h-5 w-5 transition-colors group-hover:text-[#E1306C]" />
                                </motion.a>
                            )}
                            {setting?.twitter_url && (
                                <motion.a
                                    href={setting.twitter_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="group flex h-12 w-12 items-center justify-center rounded-xl bg-white text-gray-600 shadow-md transition-all hover:shadow-lg"
                                    whileHover={{ scale: 1.1, y: -3 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    <Twitter className="h-5 w-5 transition-colors group-hover:text-[#1DA1F2]" />
                                </motion.a>
                            )}
                            {setting?.youtube_url && (
                                <motion.a
                                    href={setting.youtube_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="group flex h-12 w-12 items-center justify-center rounded-xl bg-white text-gray-600 shadow-md transition-all hover:shadow-lg"
                                    whileHover={{ scale: 1.1, y: -3 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    <Youtube className="h-5 w-5 transition-colors group-hover:text-[#FF0000]" />
                                </motion.a>
                            )}
                            {setting?.tiktok_url && (
                                <motion.a
                                    href={setting.tiktok_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="group flex h-12 w-12 items-center justify-center rounded-xl bg-white text-gray-600 shadow-md transition-all hover:shadow-lg"
                                    whileHover={{ scale: 1.1, y: -3 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    <svg className="h-5 w-5 transition-colors group-hover:text-black" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                                    </svg>
                                </motion.a>
                            )}
                            {setting?.linkedin_url && (
                                <motion.a
                                    href={setting.linkedin_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="group flex h-12 w-12 items-center justify-center rounded-xl bg-white text-gray-600 shadow-md transition-all hover:shadow-lg"
                                    whileHover={{ scale: 1.1, y: -3 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    <Linkedin className="h-5 w-5 transition-colors group-hover:text-[#0A66C2]" />
                                </motion.a>
                            )}
                        </div>
                    </motion.div>
                </motion.div>
            </div>
        </section>
    );
}
