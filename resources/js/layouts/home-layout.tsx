import React from 'react';
import Navbar from '@/components/landing/navbar';
import Footer from '@/components/landing/footer';
import { Toaster } from 'sonner';

interface Props {
    children?: React.ReactNode;
    setting?: {
        site_name: string;
        logo: string | null;
        phone: string;
        email: string;
        address: string;
        description: string;
    };
}

export default function HomeLayout({ children, setting }: Props) {
    return (
        <div id="home" className="min-h-screen overflow-x-hidden bg-white">
            <Toaster position="top-right" richColors />
            <Navbar setting={setting} />
            <main>{children}</main>
            <Footer setting={setting} />
        </div>
    );
}
