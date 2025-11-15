import HomeLayout from '@/layouts/home-layout';
import HeroSlider from '@/components/landing/hero-slider';
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

interface HomeProps {
    setting: Setting;
    heroSlides: HeroSlide[];
    routes: Route[];
    armadas: Armada[];
}

export default function Home({ setting, heroSlides, routes, armadas }: HomeProps) {
    return (
        <>
            <Head>
                <title>{setting.site_name}</title>
                <meta name="description" content={setting.description} />
                {setting.logo && <link rel="icon" type="image/png" href={setting.logo} />}
            </Head>
            <HomeLayout setting={setting}>
                <HeroSlider slides={heroSlides} />
                <Features features={setting.features} />
                <RoutesSection routes={routes} whatsappNumber={setting.whatsapp_number} />
                <ArmadaSection armadas={armadas} routes={routes} whatsappNumber={setting.whatsapp_number} />
                <MapSection setting={setting} />
                <ContactSection setting={setting} />
            </HomeLayout>
        </>
    );
}
