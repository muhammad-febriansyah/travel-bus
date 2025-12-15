import * as LucideIcons from 'lucide-react';
import { motion } from 'framer-motion';
import { Star } from 'lucide-react';

interface Feature {
    icon: string;
    title: string;
    description: string;
    rating?: string;
}

interface FeaturesProps {
    features?: Feature[];
}

export default function Features({ features: propFeatures }: FeaturesProps) {
    const containerVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                staggerChildren: 0.08,
                delayChildren: 0.05,
            },
        },
    };

    const itemVariants = {
        hidden: { opacity: 0, y: 30, scale: 0.95 },
        visible: {
            opacity: 1,
            y: 0,
            scale: 1,
            transition: {
                duration: 0.6,
                ease: [0.25, 0.46, 0.45, 0.94] // Custom cubic-bezier for smooth easing
            },
        },
    };

    // Default features jika tidak ada di database
    const defaultFeatures = [
        {
            icon: 'Shield',
            title: 'Keamanan Terjamin',
            description:
                'Armada terawat dengan standar keselamatan tinggi dan driver berpengalaman',
            rating: '4.9',
        },
        {
            icon: 'Clock',
            title: 'Tepat Waktu',
            description:
                'Jadwal keberangkatan teratur dan on-time, tanpa keterlambatan',
            rating: '4.8',
        },
        {
            icon: 'DollarSign',
            title: 'Harga Terjangkau',
            description:
                'Tarif kompetitif dengan berbagai pilihan kelas sesuai budget',
            rating: '4.7',
        },
        {
            icon: 'Headphones',
            title: 'Customer Support 24/7',
            description:
                'Tim support siap membantu Anda kapan saja via WhatsApp atau telepon',
            rating: '4.8',
        },
        {
            icon: 'Award',
            title: 'Berpengalaman',
            description:
                'Lebih dari 10 tahun melayani perjalanan antar kota dengan ribuan pelanggan puas',
            rating: '4.9',
        },
        {
            icon: 'Zap',
            title: 'Booking Mudah',
            description:
                'Sistem booking online yang cepat dan mudah, konfirmasi instant',
            rating: '4.8',
        },
    ];

    const features = propFeatures && propFeatures.length > 0 ? propFeatures : defaultFeatures;

    // Function untuk mendapatkan icon component dari nama string
    const getIconComponent = (iconName: string) => {
        const Icon = (LucideIcons as any)[iconName];
        return Icon || LucideIcons.Star; // Default to Star if icon not found
    };

    return (
        <section id="features" className="bg-white py-20 lg:py-32">
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
                        Mengapa Memilih Kami
                    </span>
                    <h2 className="mb-6 mt-4 text-3xl font-bold text-gray-900 lg:text-5xl">
                        Keunggulan Layanan Kami
                    </h2>
                    <p className="text-lg text-gray-600">
                        Kami berkomitmen memberikan pengalaman perjalanan terbaik
                        dengan berbagai fasilitas dan layanan unggulan
                    </p>
                </motion.div>

                {/* Features Grid */}
                <motion.div
                    className="grid gap-8 sm:grid-cols-2 lg:grid-cols-3"
                    variants={containerVariants}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true, amount: 0.2 }}
                >
                    {features.map((feature, index) => {
                        const IconComponent = getIconComponent(feature.icon);
                        return (
                            <motion.div
                                key={index}
                                className="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg transition-all duration-500 ease-out hover:shadow-2xl hover:-translate-y-2"
                                variants={itemVariants}
                                whileHover={{
                                    scale: 1.02,
                                    transition: {
                                        duration: 0.5,
                                        ease: [0.25, 0.46, 0.45, 0.94]
                                    }
                                }}
                            >
                                {/* Background Gradient on Hover */}
                                <div className="absolute inset-0 bg-gradient-to-br from-[#2547F9]/5 to-indigo-500/5 opacity-0 transition-opacity group-hover:opacity-100" />

                                <div className="relative">
                                    {/* Icon */}
                                    <motion.div
                                        className="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-[#2547F9]/10 text-[#2547F9]"
                                        whileHover={{
                                            rotate: [0, -10, 10, -10, 0],
                                            scale: 1.1,
                                        }}
                                        transition={{ duration: 0.5 }}
                                    >
                                        <IconComponent className="h-7 w-7" />
                                    </motion.div>

                                    {/* Title */}
                                    <h3 className="mb-3 text-xl font-bold text-gray-900">
                                        {feature.title}
                                    </h3>

                                    {/* Description */}
                                    <p className="mb-4 text-gray-600">
                                        {feature.description}
                                    </p>

                                    {/* Rating */}
                                    {feature.rating && (
                                        <motion.div
                                            className="flex items-center space-x-2"
                                            initial={{ opacity: 0 }}
                                            whileInView={{ opacity: 1 }}
                                            transition={{ delay: 0.2 }}
                                        >
                                            <div className="flex">
                                                {[...Array(5)].map((_, i) => (
                                                    <motion.div
                                                        key={i}
                                                        initial={{ opacity: 0, scale: 0 }}
                                                        whileInView={{ opacity: 1, scale: 1 }}
                                                        transition={{ delay: 0.1 * i }}
                                                    >
                                                        <Star
                                                            className={`h-4 w-4 ${
                                                                i < Math.floor(parseFloat(feature.rating))
                                                                    ? 'fill-yellow-400 text-yellow-400'
                                                                    : 'text-gray-300'
                                                            }`}
                                                        />
                                                    </motion.div>
                                                ))}
                                            </div>
                                            <span className="text-sm font-semibold text-gray-700">
                                                {feature.rating}
                                            </span>
                                        </motion.div>
                                    )}
                                </div>

                                {/* Decorative Corner */}
                                <div className="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-[#2547F9]/5 blur-2xl transition-all group-hover:bg-[#2547F9]/10" />
                            </motion.div>
                        );
                    })}
                </motion.div>
            </div>
        </section>
    );
}
