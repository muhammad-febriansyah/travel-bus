interface FooterProps {
    setting?: {
        site_name: string;
    };
}

export default function Footer({ setting }: FooterProps) {
    const currentYear = new Date().getFullYear();

    return (
        <footer className="bg-white border-t border-gray-200">
            <div className="container mx-auto px-4 py-6">
                <div className="text-center text-sm text-gray-600">
                    © {currentYear}{' '}
                    <span className="text-gray-900 font-semibold">
                        {setting?.site_name || 'Travel Bisnis'}
                    </span>
                    . All rights reserved.
                </div>
            </div>
        </footer>
    );
}
