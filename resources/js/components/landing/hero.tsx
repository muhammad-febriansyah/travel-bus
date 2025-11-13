import { Button } from '@/components/ui/button';
import { ArrowRight, Sparkles } from 'lucide-react';
import { motion } from 'framer-motion';
import { NumberTicker } from '@/components/ui/number-ticker';
import { AnimatedShinyText } from '@/components/ui/animated-shiny-text';
import { TypingAnimation } from '@/components/ui/typing-animation';

interface HeroProps {
    setting?: {
        site_name: string;
        description: string;
        hero_badge?: string;
        hero_title?: string;
        hero_subtitle?: string;
        hero_image?: string;
        hero_stats?: Array<{
            number: string;
            suffix?: string;
            label: string;
        }>;
    };
}

export default function Hero({ setting }: HeroProps) {
    const scrollToSection = (id: string) => {
        const element = document.querySelector(id);
        element?.scrollIntoView({ behavior: 'smooth' });
    };

    const containerVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                staggerChildren: 0.15,
                delayChildren: 0.2,
            },
        },
    };

    const itemVariants = {
        hidden: { opacity: 0, y: 30 },
        visible: {
            opacity: 1,
            y: 0,
            transition: { duration: 0.7, ease: [0.22, 1, 0.36, 1] },
        },
    };

    // Default stats
    const defaultStats = [
        { number: '10000', suffix: '+', label: 'Penumpang' },
        { number: '50', suffix: '+', label: 'Armada' },
        { number: '15', suffix: '+', label: 'Rute' },
    ];

    const stats = setting?.hero_stats && setting.hero_stats.length > 0
        ? setting.hero_stats
        : defaultStats;

    return (
        <section
            id="home"
            className="relative min-h-screen flex items-center overflow-hidden bg-white"
        >
            {/* Subtle Background Decoration */}
            <div className="absolute inset-0 overflow-hidden">
                <div className="absolute -right-1/4 top-0 h-[600px] w-[600px] rounded-full bg-[#2547F9]/5 blur-3xl" />
                <div className="absolute -left-1/4 bottom-0 h-[600px] w-[600px] rounded-full bg-indigo-500/5 blur-3xl" />
            </div>

            <div className="container relative z-10 mx-auto px-4 py-20 lg:py-32">
                <div className="grid items-center gap-16 lg:grid-cols-2">
                    {/* Left Content */}
                    <motion.div
                        variants={containerVariants}
                        initial="hidden"
                        animate="visible"
                        className="space-y-6"
                    >
                        {/* Badge */}
                        <motion.div variants={itemVariants}>
                            <div className="inline-flex items-center gap-2 rounded-full border border-[#2547F9]/20 bg-[#2547F9]/5 px-4 py-2">
                                <Sparkles className="h-4 w-4 text-[#2547F9]" />
                                <AnimatedShinyText className="text-sm font-medium text-[#2547F9]">
                                    {setting?.hero_badge || '#1 Layanan Travel Terpercaya'}
                                </AnimatedShinyText>
                            </div>
                        </motion.div>

                        {/* Title */}
                        <motion.div variants={itemVariants} className="space-y-3">
                            <h1 className="text-3xl font-bold leading-[1.1] text-gray-900 lg:text-4xl xl:text-5xl">
                                <TypingAnimation
                                    words={["Perjalanan Nyaman", "Perjalanan Aman", "Perjalanan Terpercaya"]}
                                    className="text-3xl font-bold leading-[1.1] text-[#2547F9] lg:text-4xl xl:text-5xl"
                                    loop
                                />
                            </h1>

                            <p className="text-base leading-relaxed text-gray-600 lg:text-lg lg:leading-relaxed">
                                {setting?.hero_subtitle ||
                                    setting?.description ||
                                    'Nikmati perjalanan antar kota dengan armada berkualitas, harga terjangkau, dan pelayanan terbaik.'}
                            </p>
                        </motion.div>

                        {/* CTA Buttons */}
                        <motion.div
                            variants={itemVariants}
                            className="flex flex-col gap-4 sm:flex-row"
                        >
                            <Button
                                size="lg"
                                className="group h-14 rounded-xl bg-[#2547F9] px-8 text-base font-semibold text-white shadow-lg shadow-[#2547F9]/20 transition-all hover:bg-[#1d3acc] hover:shadow-xl hover:shadow-[#2547F9]/30"
                                onClick={() => scrollToSection('#routes')}
                            >
                                Lihat Rute Perjalanan
                                <ArrowRight className="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" />
                            </Button>
                            <Button
                                size="lg"
                                variant="outline"
                                className="h-14 rounded-xl border-2 border-gray-200 bg-white px-8 text-base font-semibold text-gray-900 transition-all hover:border-[#2547F9] hover:bg-[#2547F9] hover:text-white"
                                onClick={() => scrollToSection('#contact')}
                            >
                                Hubungi Kami
                            </Button>
                        </motion.div>

                        {/* Stats */}
                        <motion.div
                            variants={itemVariants}
                            className="grid grid-cols-3 gap-6 border-t border-gray-200 pt-6"
                        >
                            {stats.map((stat, index) => (
                                <div key={index} className="text-center lg:text-left">
                                    <div className="mb-1 flex items-baseline justify-center gap-1 lg:justify-start">
                                        <NumberTicker
                                            value={parseInt(stat.number)}
                                            className="text-2xl font-bold text-gray-900 lg:text-3xl"
                                        />
                                        <span className="text-2xl font-bold text-[#2547F9] lg:text-3xl">{stat.suffix || ''}</span>
                                    </div>
                                    <div className="text-xs text-gray-600 lg:text-sm">
                                        {stat.label}
                                    </div>
                                </div>
                            ))}
                        </motion.div>
                    </motion.div>

                    {/* Right Content - Image */}
                    <motion.div
                        initial={{ opacity: 0, scale: 0.9 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 0.8, delay: 0.3 }}
                        className="relative"
                    >
                        <div className="relative">
                            {setting?.hero_image ? (
                                <div className="relative overflow-hidden rounded-3xl">
                                    <img
                                        src={setting.hero_image}
                                        alt="Hero"
                                        className="w-full object-cover shadow-2xl"
                                    />
                                    {/* Overlay Gradient */}
                                    <div className="absolute inset-0 bg-gradient-to-t from-gray-900/10 to-transparent" />
                                </div>
                            ) : (
                                <div className="relative aspect-[4/3] overflow-hidden rounded-3xl bg-gradient-to-br from-gray-50 to-gray-100 shadow-2xl">
                                    <div className="flex h-full items-center justify-center">
                                        <div className="text-center">
                                            <div className="mb-6 text-8xl">🚌</div>
                                            <p className="text-2xl font-bold text-gray-900">
                                                {setting?.site_name || 'Travel'}
                                            </p>
                                        </div>
                                    </div>
                                    {/* Decorative Elements */}
                                    <div className="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-[#2547F9]/10" />
                                    <div className="absolute -bottom-8 -left-8 h-40 w-40 rounded-full bg-indigo-500/10" />
                                </div>
                            )}

                            {/* Floating Card */}
                            <motion.div
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ delay: 1, duration: 0.6 }}
                                className="absolute -bottom-6 -left-6 rounded-2xl bg-white p-6 shadow-2xl"
                            >
                                <div className="flex items-center gap-4">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-[#2547F9]/10">
                                        <svg
                                            className="h-6 w-6 text-[#2547F9]"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <div className="text-sm font-medium text-gray-600">
                                            Kepuasan Pelanggan
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <div className="flex">
                                                {[...Array(5)].map((_, i) => (
                                                    <svg
                                                        key={i}
                                                        className="h-4 w-4 text-yellow-400"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                ))}
                                            </div>
                                            <span className="text-base font-bold text-gray-900">
                                                4.9/5
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </motion.div>

                            {/* Floating Badge */}
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 1.2, duration: 0.6 }}
                                className="absolute -right-6 top-1/4 rounded-2xl bg-white p-4 shadow-2xl"
                            >
                                <div className="text-center">
                                    <div className="mb-1 text-3xl font-bold text-[#2547F9]">
                                        24/7
                                    </div>
                                    <div className="text-xs font-medium text-gray-600">
                                        Customer Service
                                    </div>
                                </div>
                            </motion.div>
                        </div>
                    </motion.div>
                </div>
            </div>
        </section>
    );
}
