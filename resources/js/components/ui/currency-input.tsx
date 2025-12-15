import * as React from 'react';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';

export interface CurrencyInputProps
    extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'onChange' | 'value'> {
    value?: number;
    onValueChange?: (value: number) => void;
}

const CurrencyInput = React.forwardRef<HTMLInputElement, CurrencyInputProps>(
    ({ className, value = 0, onValueChange, ...props }, ref) => {
        const [displayValue, setDisplayValue] = React.useState('');

        React.useEffect(() => {
            // Format value to display
            setDisplayValue(formatRupiah(value));
        }, [value]);

        const formatRupiah = (num: number): string => {
            if (!num) return '';
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        };

        const parseRupiah = (str: string): number => {
            const cleaned = str.replace(/\./g, '');
            const num = parseInt(cleaned) || 0;
            return num;
        };

        const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
            const input = e.target.value;

            // Remove all non-numeric characters except dots
            const cleaned = input.replace(/[^\d]/g, '');

            // Parse to number
            const numValue = parseInt(cleaned) || 0;

            // Update display
            setDisplayValue(formatRupiah(numValue));

            // Call onChange with numeric value
            if (onValueChange) {
                onValueChange(numValue);
            }
        };

        return (
            <div className="relative">
                <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                    Rp
                </span>
                <Input
                    type="text"
                    className={cn('pl-10', className)}
                    ref={ref}
                    value={displayValue}
                    onChange={handleChange}
                    {...props}
                />
            </div>
        );
    }
);

CurrencyInput.displayName = 'CurrencyInput';

export { CurrencyInput };
