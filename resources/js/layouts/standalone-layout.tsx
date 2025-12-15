import { type ReactNode } from 'react';
import { Armchair, LogOut } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Toaster } from '@/components/ui/sonner';
import { router } from '@inertiajs/react';

interface StandaloneLayoutProps {
    children: ReactNode;
}

export default function StandaloneLayout({ children }: StandaloneLayoutProps) {
    const handleBackToAdmin = () => {
        window.close();
        // If window.close() doesn't work (not opened by script), redirect
        setTimeout(() => {
            router.visit('/admin');
        }, 100);
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50 dark:from-slate-950 dark:via-blue-950 dark:to-slate-950">
            {/* Modern Header with Gradient */}
            <header className="sticky top-0 z-50 w-full border-b border-slate-200/50 dark:border-slate-800/50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-slate-950/60 shadow-sm">
                <div className="container mx-auto px-4">
                    <div className="flex h-16 items-center justify-between">
                        <div className="flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-blue-700 shadow-lg shadow-blue-500/30">
                                <Armchair className="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h1 className="text-lg font-bold bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
                                    Seat Booking System
                                </h1>
                                <p className="text-xs text-muted-foreground">Travel Bisnis Sumatera Barat</p>
                            </div>
                        </div>

                        <Button
                            variant="outline"
                            size="sm"
                            onClick={handleBackToAdmin}
                            className="gap-2"
                        >
                            <LogOut className="h-4 w-4" />
                            Kembali ke Admin
                        </Button>
                    </div>
                </div>
            </header>

            {/* Main Content with Better Background */}
            <main className="flex-1 min-h-[calc(100vh-8rem)]">
                {children}
            </main>

            {/* Modern Footer */}
            <footer className="border-t border-slate-200/50 dark:border-slate-800/50 bg-white/50 dark:bg-slate-950/50 backdrop-blur-sm">
                <div className="container mx-auto px-4 py-4">
                    <p className="text-center text-sm text-muted-foreground">
                        © {new Date().getFullYear()} Travel Bisnis Sumatera Barat. All rights reserved.
                    </p>
                </div>
            </footer>

            <Toaster position="top-right" richColors />
        </div>
    );
}
