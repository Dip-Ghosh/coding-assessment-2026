<?php

/**
 * InvoiceCalculator - Helper class for invoice calculations
 *
 * Static utility methods for business logic
 * Client keeps changing their mind on requirements...
 */
class InvoiceCalculator
{
    public static function calculateTax($subtotal, $region = 'US-CA')
    {
        if ($subtotal <= 0) {
            return 0;
        }

        $taxRate = 0.10;
        $taxFile = dirname(__DIR__).'/data/tax_rates.json';

        if (file_exists($taxFile)) {
            $taxData = json_decode(file_get_contents($taxFile), true);
            $parts   = explode('-', $region);
            $country = $parts[0] ?? '';
            $state   = $parts[1] ?? 'default';

            if (isset($taxData[$country])) {
                $taxRate = $taxData[$country][$state] ?? $taxData[$country]['default'] ?? $taxRate;
            }
        }

        return round($subtotal * $taxRate, 2);
    }

    /**
     * Apply business rules to an invoice
     *
     * Rules from client (received via email last Thursday):
     * 1. Orders over $1000 get automatic 5% discount
     * 2. BUT discount should NOT apply to items marked as "sale" items
     * 3. How do we even track which items are on sale??
     * 4. Does the $1000 include tax or not?? (Waiting for response)
     *
     * Client keeps changing their mind on this feature
     * Started implementation 3 times, gave up
     *
     * @param Invoice $invoice
     * @return Invoice Modified invoice
     */
    public static function applyBusinessRules($invoice) {
        // Need to figure out requirements first

        // Pseudo-code for what they MIGHT want:
        // if (invoice total > 1000 && !has_sale_items) {
        //     apply 5% discount
        // }

        // Problems:
        // 1. How to identify sale items? Add a flag to item array?
        // 2. Does discount apply before or after tax?
        // 3. Can discounts stack with other discounts?
        // 4. What if they return items - does discount get recalculated?

        // For now, just return the invoice unchanged
        // Need meeting with client to clarify

        return $invoice;
    }

    /**
     * Calculate line item total
     * This one actually works correctly!
     *
     * @param array $item Item with price and quantity/qty
     * @return float Line item total
     */
    public static function calculateLineItem($item) {
        $price = $item['price'];

        // Handle both 'quantity' and 'qty' naming
        // (Someone was inconsistent with naming)
        $quantity = isset($item['quantity']) ? $item['quantity'] : $item['qty'];

        return $price * $quantity;
    }

    /**
     * Format currency for display
     * Quick helper I added
     *
     * @param float $amount
     * @return string Formatted currency
     */
    public static function formatCurrency($amount) {
        return '$' . number_format($amount, 2);
    }

    /**
     * Validate invoice data
     * Started but didn't finish
     *
     * Should check:
     * - No negative prices
     * - No negative quantities
     * - Customer name not empty
     * - At least one item
     * - etc.
     */
    public static function validateInvoice($invoice) {
        $errors = [];

        // TODO: Add actual validation logic

        return $errors;
    }
}
