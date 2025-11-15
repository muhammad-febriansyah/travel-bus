import { useState, useCallback, useEffect } from 'react';
import { Button } from '@/components/ui/button';
import { ArrowRight, ChevronLeft, ChevronRight } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import useEmblaCarousel from 'embla-carousel-react';
import Autoplay from 'embla-carousel-autoplay';

interface HeroSlide {
    id: number;
    title: string;
    subtitle: string | null;
    description: string | null;
    image: string | null;
    badge_text: string | null;
    primary_button_text: string | null;
    primary_button_url: string | null;
    secondary_button_text: string | null;
    secondary_button_url: string | null;
    rating_text: string | null;
    rating_value: number | null;
}

interface HeroSliderProps {
    slides: HeroSlide[];
}

export default function HeroSlider({ slides }: HeroSliderProps) {
    const [selectedIndex, setSelectedIndex] = useState(0);

    const [emblaRef, emblaApi] = useEmblaCarousel(
        { loop: true, duration: 30 },
        [Autoplay({ delay: 5000, stopOnInteraction: false })]
    );

    const scrollPrev = useCallback(() => {
        if (emblaApi) emblaApi.scrollPrev();
    }, [emblaApi]);

    const scrollNext = useCallback(() => {
        if (emblaApi) emblaApi.scrollNext();
    }, [emblaApi]);

    const scrollTo = useCallback(
        (index: number) => {
            if (emblaApi) emblaApi.scrollTo(index);
        },
        [emblaApi]
    );

    const onSelect = useCallback(() => {
        if (!emblaApi) return;
        setSelectedIndex(emblaApi.selectedScrollSnap());
    }, [emblaApi]);

    useEffect(() => {
        if (!emblaApi) return;
        onSelect();
        emblaApi.on('select', onSelect);
        return () => {
            emblaApi.off('select', onSelect);
        };
    }, [emblaApi, onSelect]);

    const scrollToSection = (id: string) => {
        const element = document.querySelector(id);
        element?.scrollIntoView({ behavior: 'smooth' });
    };

    const handleButtonClick = (url: string | null) => {
        if (!url) return;

        if (url.startsWith('#')) {
            scrollToSection(url);
        } else {
            window.location.href = url;
        }
    };

    if (!slides || slides.length === 0) {
        return null;
    }

    return (
        <section
            id="home"
            className="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-gray-50 to-blue-50"
        >
            {/* Background Decoration */}
            <div className="absolute inset-0 overflow-hidden pointer-events-none">
                <div className="absolute -right-1/4 top-0 h-[800px] w-[800px] rounded-full bg-[#2547F9]/5 blur-3xl" />
                <div className="absolute -left-1/4 bottom-0 h-[800px] w-[800px] rounded-full bg-indigo-500/5 blur-3xl" />
            </div>

            {/* Carousel */}
            <div className="w-full overflow-hidden" ref={emblaRef}>
                <div className="flex">
                    {slides.map((slide, index) => (
                        <div key={slide.id} className="flex-[0_0_100%] min-w-0">
                            <div className="container relative z-10 mx-auto px-4 py-20 lg:py-32">
                                <div className="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                                    {/* Left Content */}
                                    <motion.div
                                        initial={{ opacity: 0, x: -50 }}
                                        animate={{ opacity: 1, x: 0 }}
                                        transition={{ duration: 0.6, delay: 0.2 }}
                                        className="space-y-6 lg:space-y-8"
                                    >
                                        {/* Badge */}
                                        {slide.badge_text && (
                                            <motion.div
                                                initial={{ opacity: 0, y: 20 }}
                                                animate={{ opacity: 1, y: 0 }}
                                                transition={{ delay: 0.3 }}
                                            >
                                                <div className="inline-flex items-center gap-2 rounded-full border border-[#2547F9]/20 bg-[#2547F9]/10 px-4 py-2 backdrop-blur-sm">
                                                    <span className="relative flex h-2 w-2">
                                                        <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#2547F9] opacity-75"></span>
                                                        <span className="relative inline-flex h-2 w-2 rounded-full bg-[#2547F9]"></span>
                                                    </span>
                                                    <span className="text-sm font-semibold text-[#2547F9]">
                                                        {slide.badge_text}
                                                    </span>
                                                </div>
                                            </motion.div>
                                        )}

                                        {/* Title & Description */}
                                        <motion.div
                                            initial={{ opacity: 0, y: 20 }}
                                            animate={{ opacity: 1, y: 0 }}
                                            transition={{ delay: 0.4 }}
                                            className="space-y-4"
                                        >
                                            <h1 className="text-4xl font-bold leading-tight text-gray-900 lg:text-5xl xl:text-6xl">
                                                {slide.title}
                                            </h1>

                                            {slide.description && (
                                                <p className="text-base leading-relaxed text-gray-600 lg:text-lg lg:leading-relaxed">
                                                    {slide.description}
                                                </p>
                                            )}
                                        </motion.div>

                                        {/* CTA Buttons */}
                                        <motion.div
                                            initial={{ opacity: 0, y: 20 }}
                                            animate={{ opacity: 1, y: 0 }}
                                            transition={{ delay: 0.5 }}
                                            className="flex flex-col gap-4 sm:flex-row"
                                        >
                                            {slide.primary_button_text && (
                                                <Button
                                                    size="lg"
                                                    className="group h-14 rounded-xl bg-[#2547F9] px-8 text-base font-semibold text-white shadow-lg shadow-[#2547F9]/20 transition-all hover:scale-105 hover:bg-[#1d3acc] hover:shadow-xl hover:shadow-[#2547F9]/30"
                                                    onClick={() => handleButtonClick(slide.primary_button_url)}
                                                >
                                                    {slide.primary_button_text}
                                                    <ArrowRight className="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" />
                                                </Button>
                                            )}
                                            {slide.secondary_button_text && (
                                                <Button
                                                    size="lg"
                                                    variant="outline"
                                                    className="h-14 rounded-xl border-2 border-gray-300 bg-white/80 px-8 text-base font-semibold text-gray-900 backdrop-blur-sm transition-all hover:scale-105 hover:border-[#2547F9] hover:bg-[#2547F9] hover:text-white"
                                                    onClick={() => handleButtonClick(slide.secondary_button_url)}
                                                >
                                                    {slide.secondary_button_text}
                                                </Button>
                                            )}
                                        </motion.div>
                                    </motion.div>

                                    {/* Right Content - Image or Default */}
                                    <motion.div
                                        initial={{ opacity: 0, x: 50 }}
                                        animate={{ opacity: 1, x: 0 }}
                                        transition={{ duration: 0.6, delay: 0.3 }}
                                        className="relative"
                                    >
                                        <div className="relative">
                                            {slide.image ? (
                                                <div className="relative overflow-hidden rounded-3xl shadow-2xl">
                                                    <img
                                                        src={slide.image}
                                                        alt={slide.title}
                                                        className="w-full h-auto object-cover"
                                                        onError={(e) => {
                                                            e.currentTarget.style.display = 'none';
                                                            const fallback = e.currentTarget.parentElement?.nextElementSibling;
                                                            if (fallback) fallback.classList.remove('hidden');
                                                        }}
                                                    />
                                                    <div className="absolute inset-0 bg-gradient-to-t from-gray-900/20 to-transparent" />
                                                </div>
                                            ) : null}
                                            {/* Fallback when no image or image error */}
                                            <div className={`relative aspect-[4/3] overflow-hidden rounded-3xl bg-gradient-to-br from-[#2547F9]/10 to-indigo-500/10 shadow-2xl ${slide.image ? 'hidden' : ''}`}>
                                                <div className="flex h-full items-center justify-center">
                                                    <div className="text-center">
                                                        <div className="mb-6 text-8xl">🚌</div>
                                                        <p className="text-3xl font-bold text-gray-900">
                                                            {slide.title}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Rating Card */}
                                            {slide.rating_value && (
                                                <motion.div
                                                    initial={{ opacity: 0, y: 20 }}
                                                    animate={{ opacity: 1, y: 0 }}
                                                    transition={{ delay: 0.8 }}
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
                                                                {slide.rating_text || 'Rating'}
                                                            </div>
                                                            <div className="flex items-center gap-2">
                                                                <div className="flex">
                                                                    {[...Array(5)].map((_, i) => (
                                                                        <svg
                                                                            key={i}
                                                                            className={`h-4 w-4 ${i < Math.floor(slide.rating_value || 0) ? 'text-yellow-400' : 'text-gray-300'}`}
                                                                            fill="currentColor"
                                                                            viewBox="0 0 20 20"
                                                                        >
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                        </svg>
                                                                    ))}
                                                                </div>
                                                                <span className="text-base font-bold text-gray-900">
                                                                    {slide.rating_value}/5
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </motion.div>
                                            )}

                                            {/* Subtitle Badge */}
                                            {slide.subtitle && (
                                                <motion.div
                                                    initial={{ opacity: 0, x: 20 }}
                                                    animate={{ opacity: 1, x: 0 }}
                                                    transition={{ delay: 1 }}
                                                    className="absolute -right-6 top-1/4 rounded-2xl bg-white p-4 shadow-2xl"
                                                >
                                                    <div className="text-center">
                                                        <div className="mb-1 text-xl font-bold text-[#2547F9]">
                                                            {slide.subtitle.split(' ')[0]}
                                                        </div>
                                                        <div className="text-xs font-medium text-gray-600">
                                                            {slide.subtitle.split(' ').slice(1).join(' ')}
                                                        </div>
                                                    </div>
                                                </motion.div>
                                            )}
                                        </div>
                                    </motion.div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            {/* Navigation Arrows */}
            {slides.length > 1 && (
                <>
                    <button
                        onClick={scrollPrev}
                        className="absolute left-4 top-1/2 z-20 -translate-y-1/2 rounded-full bg-white/90 p-3 shadow-lg backdrop-blur-sm transition-all hover:bg-white hover:scale-110 lg:left-8"
                    >
                        <ChevronLeft className="h-6 w-6 text-gray-900" />
                    </button>
                    <button
                        onClick={scrollNext}
                        className="absolute right-4 top-1/2 z-20 -translate-y-1/2 rounded-full bg-white/90 p-3 shadow-lg backdrop-blur-sm transition-all hover:bg-white hover:scale-110 lg:right-8"
                    >
                        <ChevronRight className="h-6 w-6 text-gray-900" />
                    </button>
                </>
            )}

            {/* Dots Navigation */}
            {slides.length > 1 && (
                <div className="absolute bottom-8 left-1/2 z-20 flex -translate-x-1/2 gap-2">
                    {slides.map((_, index) => (
                        <button
                            key={index}
                            onClick={() => scrollTo(index)}
                            className={`h-2 rounded-full transition-all ${
                                index === selectedIndex
                                    ? 'w-8 bg-[#2547F9]'
                                    : 'w-2 bg-gray-400 hover:bg-gray-600'
                            }`}
                        />
                    ))}
                </div>
            )}
        </section>
    );
}
