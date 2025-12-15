import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import {
    AnimatePresence,
    motion,
    useScroll,
    useTransform,
} from 'framer-motion';
import { Menu, X } from 'lucide-react';
import { useState } from 'react';

interface NavbarProps {
    setting?: {
        site_name: string;
        logo: string | null;
        phone: string;
        email: string;
    };
}

export default function Navbar({ setting }: NavbarProps) {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const { scrollY } = useScroll();

    // Transform values based on scroll
    const navWidth = useTransform(scrollY, [0, 100], ['100%', '95%']);
    const navPadding = useTransform(scrollY, [0, 100], ['1.5rem', '0.75rem']);
    const navBorderRadius = useTransform(scrollY, [0, 100], ['0px', '16px']);
    const navMarginTop = useTransform(scrollY, [0, 100], ['0px', '1rem']);
    const logoScale = useTransform(scrollY, [0, 100], [1, 0.85]);
    const navBlur = useTransform(
        scrollY,
        [0, 100],
        ['blur(0px)', 'blur(12px)'],
    );
    const navBg = useTransform(
        scrollY,
        [0, 100],
        ['rgba(255, 255, 255, 1)', 'rgba(255, 255, 255, 0.7)'],
    );
    const navBorder = useTransform(
        scrollY,
        [0, 100],
        ['rgba(229, 231, 235, 0)', 'rgba(229, 231, 235, 0.2)'],
    );

    const scrollToSection = (
        e: React.MouseEvent<HTMLAnchorElement>,
        href: string,
    ) => {
        e.preventDefault();
        const element = document.querySelector(href);
        if (element) {
            const offset = 100;
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition =
                elementPosition + window.pageYOffset - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth',
            });
        }
        setIsMobileMenuOpen(false);
    };

    const navLinks = [
        { href: '/', label: 'Beranda', isHome: true },
        { href: '#routes', label: 'Rute', isHome: false },
        { href: '#armada', label: 'Armada', isHome: false },
        { href: '#features', label: 'Layanan', isHome: false },
        { href: '#contact', label: 'Kontak', isHome: false },
    ];

    return (
        <div className="fixed top-0 right-0 left-0 z-50 flex justify-center">
            <motion.nav
                className="relative border"
                style={{
                    width: navWidth,
                    paddingTop: navPadding,
                    paddingBottom: navPadding,
                    borderRadius: navBorderRadius,
                    marginTop: navMarginTop,
                    backdropFilter: navBlur,
                    WebkitBackdropFilter: navBlur,
                    backgroundColor: navBg,
                    borderColor: navBorder,
                }}
                initial={{ y: -100, opacity: 0 }}
                animate={{ y: 0, opacity: 1 }}
                transition={{ duration: 0.6, ease: 'easeOut' }}
            >
                <div className="container mx-auto px-4">
                    <div className="flex items-center justify-between">
                        {/* Logo */}
                        <Link href="/" className="flex items-center space-x-3">
                            <motion.div
                                style={{ scale: logoScale }}
                                className="flex items-center space-x-2"
                            >
                                {setting?.logo ? (
                                    <img
                                        src={setting.logo}
                                        alt={setting.site_name}
                                        className="h-20 w-full object-cover"
                                    />
                                ) : (
                                    <>
                                        <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#2547F9] to-indigo-600">
                                            <span className="text-xl font-bold text-white">
                                                {setting?.site_name.charAt(0) ||
                                                    'T'}
                                            </span>
                                        </div>
                                        <span className="text-xl font-bold text-gray-900">
                                            {setting?.site_name || 'Travel'}
                                        </span>
                                    </>
                                )}
                            </motion.div>
                        </Link>

                        {/* Desktop Navigation */}
                        <div className="hidden items-center space-x-1 lg:flex">
                            {navLinks.map((link, index) => (
                                <motion.a
                                    key={link.href}
                                    href={link.href}
                                    onClick={(e) => {
                                        if (!link.isHome) {
                                            scrollToSection(e, link.href);
                                        }
                                    }}
                                    className="relative cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:text-[#2547F9]"
                                    initial={{ opacity: 0, y: -20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ delay: index * 0.1 }}
                                    whileHover={{ scale: 1.05 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    {link.label}
                                    <motion.div
                                        className="absolute bottom-0 left-0 h-0.5 w-full bg-[#2547F9]"
                                        initial={{ scaleX: 0 }}
                                        whileHover={{ scaleX: 1 }}
                                        transition={{ duration: 0.3 }}
                                    />
                                </motion.a>
                            ))}
                        </div>

                        {/* Contact & CTA */}
                        <div className="hidden items-center space-x-3 lg:flex">
                            <Link href="/booking">
                                <motion.div
                                    whileHover={{ scale: 1.05 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    <Button className="bg-gradient-to-r from-green-600 to-green-700 text-white shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40">
                                        Pesan Sekarang
                                    </Button>
                                </motion.div>
                            </Link>
                            <Link href="/cek-booking">
                                <motion.div
                                    whileHover={{ scale: 1.05 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    <Button
                                        variant="outline"
                                        className="border-[#2547F9] text-[#2547F9] hover:bg-[#2547F9] hover:text-white"
                                    >
                                        Cek Booking
                                    </Button>
                                </motion.div>
                            </Link>
                            <a href="/admin/login" target="_blank">
                                <motion.div
                                    whileHover={{ scale: 1.05 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    <Button className="bg-gradient-to-r from-[#2547F9] to-indigo-600 text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40">
                                        Admin Login
                                    </Button>
                                </motion.div>
                            </a>
                        </div>

                        {/* Mobile Menu Button */}
                        <motion.button
                            onClick={() =>
                                setIsMobileMenuOpen(!isMobileMenuOpen)
                            }
                            className="rounded-lg p-2 transition-colors hover:bg-gray-100 lg:hidden"
                            whileHover={{ scale: 1.1 }}
                            whileTap={{ scale: 0.9 }}
                        >
                            <AnimatePresence mode="wait">
                                {isMobileMenuOpen ? (
                                    <motion.div
                                        key="close"
                                        initial={{ rotate: -90, opacity: 0 }}
                                        animate={{ rotate: 0, opacity: 1 }}
                                        exit={{ rotate: 90, opacity: 0 }}
                                        transition={{ duration: 0.2 }}
                                    >
                                        <X className="h-6 w-6 text-gray-700" />
                                    </motion.div>
                                ) : (
                                    <motion.div
                                        key="menu"
                                        initial={{ rotate: 90, opacity: 0 }}
                                        animate={{ rotate: 0, opacity: 1 }}
                                        exit={{ rotate: -90, opacity: 0 }}
                                        transition={{ duration: 0.2 }}
                                    >
                                        <Menu className="h-6 w-6 text-gray-700" />
                                    </motion.div>
                                )}
                            </AnimatePresence>
                        </motion.button>
                    </div>

                    {/* Mobile Menu */}
                    <AnimatePresence>
                        {isMobileMenuOpen && (
                            <motion.div
                                className="mt-4 border-t border-gray-200 py-4 lg:hidden"
                                initial={{ opacity: 0, height: 0 }}
                                animate={{ opacity: 1, height: 'auto' }}
                                exit={{ opacity: 0, height: 0 }}
                                transition={{ duration: 0.3 }}
                            >
                                <div className="space-y-2">
                                    {navLinks.map((link, index) => (
                                        <motion.a
                                            key={link.href}
                                            href={link.href}
                                            onClick={(e) => {
                                                if (!link.isHome) {
                                                    scrollToSection(e, link.href);
                                                } else {
                                                    setIsMobileMenuOpen(false);
                                                }
                                            }}
                                            className="block cursor-pointer rounded-lg px-4 py-3 font-medium text-gray-700 transition-colors hover:bg-gray-100 hover:text-[#2547F9]"
                                            initial={{ opacity: 0, x: -20 }}
                                            animate={{ opacity: 1, x: 0 }}
                                            transition={{ delay: index * 0.1 }}
                                            whileTap={{ scale: 0.95 }}
                                        >
                                            {link.label}
                                        </motion.a>
                                    ))}
                                </div>

                                <div className="mt-4 space-y-2 border-t border-gray-200 pt-4">
                                    <Link href="/booking" className="block">
                                        <Button className="w-full bg-gradient-to-r from-green-600 to-green-700 text-white">
                                            Pesan Sekarang
                                        </Button>
                                    </Link>
                                    <Link href="/cek-booking" className="block">
                                        <Button
                                            variant="outline"
                                            className="w-full border-[#2547F9] text-[#2547F9]"
                                        >
                                            Cek Booking
                                        </Button>
                                    </Link>
                                    <Link
                                        href="/admin/login"
                                        target="_blank"
                                        className="block"
                                    >
                                        <Button className="w-full bg-gradient-to-r from-[#2547F9] to-indigo-600 text-white">
                                            Admin Login
                                        </Button>
                                    </Link>
                                </div>
                            </motion.div>
                        )}
                    </AnimatePresence>
                </div>
            </motion.nav>
        </div>
    );
}
