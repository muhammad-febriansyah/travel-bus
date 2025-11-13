import HomeLayout from '@/layouts/home-layout';
import Hero from '@/components/landing/hero';
import Features from '@/components/landing/features';
import RoutesSection from '@/components/landing/routes-section';
import ArmadaSection from '@/components/landing/armada-section';
import MapSection from '@/components/landing/map-section';
import ContactSection from '@/components/landing/contact-section';
import { Head } from '@inertiajs/react';

interface Setting {
    site_name: string;
    logo: string | null;
    phone: string;
    email: string;
    address: string;
    google_maps_embed?: string;
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
    features?: Array<{
        icon: string;
        title: string;
        description: string;
        rating?: string;
    }>;
    facebook_url?: string;
    instagram_url?: string;
    twitter_url?: string;
    whatsapp_number?: string;
    youtube_url?: string;
    tiktok_url?: string;
    linkedin_url?: string;
}

interface Route {
    id: number;
    origin: string;
    destination: string;
    route_code: string;
    distance: number;
    estimated_duration: number;
    prices: Array<{
        category: string;
        price: number;
    }>;
}

interface Armada {
    id: number;
    name: string;
    vehicle_type: string;
    capacity: number;
    category: string;
    description: string;
    image: string | null;
}

interface HomeProps {
    setting: Setting;
    routes: Route[];
    armadas: Armada[];
}

export default function Home({ setting, routes, armadas }: HomeProps) {
    return (
        <>
            <Head>
                <title>{setting.site_name}</title>
                <meta name="description" content={setting.description} />
                {setting.logo && <link rel="icon" type="image/png" href={setting.logo} />}
            </Head>
            <HomeLayout setting={setting}>
                <Hero setting={setting} />
                <Features features={setting.features} />
                <RoutesSection routes={routes} whatsappNumber={setting.whatsapp_number} />
                <ArmadaSection armadas={armadas} routes={routes} whatsappNumber={setting.whatsapp_number} />
                <MapSection setting={setting} />
                <ContactSection setting={setting} />
            </HomeLayout>
        </>
    );
}
