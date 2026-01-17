<?php

class Invoice
{
    private $customer;
    private $items = [];
    private $discount = 0;
    private $id;
    private $createdAt;

    public function __construct($customerName)
    {
        $this->customer  = $customerName;
        $this->id        = $this->generateInvoiceId();
        $this->createdAt = date('Y-m-d H:i:s');
    }

    private function generateInvoiceId()
    {
        return 'INV-' . date('Ymd') . '-' . random_int(1000, 9999);
    }

    public function addItem($name, $price, $quantity)
    {
        $name = trim($name);
        $this->validateItem($name, $price, $quantity);
        $this->items[] = [
            'name'     => $name,
            'price'    => $price,
            'quantity' => $quantity,
        ];
    }

    private function  validateItem($name, $price, $quantity)
    {
        if ($name === '') {
            throw new InvalidArgumentException('Item name cannot be empty');
        }

        if (!is_numeric($price) || $price < 0) {
            throw new InvalidArgumentException('Item price must be numeric and it should be greater than equal to 0');
        }

        if ($quantity < 1) {
            throw new InvalidArgumentException('Item quantity must be greater than 0 or equal to 1');
        }
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total - $this->discount;
    }

    /**
     * Apply discount to invoice
     * TODO: Should discounts apply before or after tax?
     * TODO: Client hasn't decided on the business rules yet
     */
    public function applyDiscount($percent) {
        // Started implementing but not sure about requirements
        // throw new Exception("Not implemented - waiting on client clarification");

        // Trying basic implementation but commented out until we get clarity
        // $subtotal = $this->getTotal();
        // $this->discount = $subtotal * ($percent / 100);

        // For now just throw exception
        throw new Exception("Discount feature incomplete - need business rules from client");
    }

    public function getId() {
        return $this->id;
    }

    public function getCustomer() {
        return $this->customer;
    }

    public function getItems() {
        return $this->items;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'customer' => $this->customer,
            'items' => $this->items,
            'discount' => $this->discount,
            'total' => $this->getTotal(),
            'created_at' => $this->createdAt,
        ];
    }

    public function saveToFile($filename = 'data/invoices.json')
    {
        $this->isDirectoryExists($filename);
        $invoices = $this->loadInvoices($filename);

        if ($this->isInvoiceExists($invoices)) {
            throw new RuntimeException("Invoice already exists: {$this->id}");
        }

        $invoices[] = $this->toArray();

        $this->writeInvoices($filename, $invoices);

        return true;
    }

    private function isDirectoryExists($filename)
    {
        $directory = dirname($filename);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    private function loadInvoices($filename)
    {
        if (!file_exists($filename)) {
            return [];
        }

        $contents = file_get_contents($filename);
        if ($contents === false) {
            throw new RuntimeException('Unable to read invoice file');
        }

        $data = json_decode($contents, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON in invoice file');
        }

        return isset($data['id']) ? [$data] : (array) $data;
    }

    private function isInvoiceExists($invoices)
    {
        foreach ($invoices as $invoice) {
            if (($invoice['id'] ?? null) === $this->id) {
                return true;
            }
        }

        return false;
    }

    private function writeInvoices($filename, $invoices)
    {
        $json = json_encode($invoices, JSON_PRETTY_PRINT);
        if ($json === false) {
            throw new RuntimeException('Failed to encode invoice data');
        }

        $tmpFile = $filename . '.tmp';

        if (file_put_contents($tmpFile, $json, LOCK_EX) === false) {
            throw new RuntimeException('Failed to write invoice file');
        }

        rename($tmpFile, $filename);
    }

    /**
     * Load invoice from file by ID
     * Started this but didn't finish testing it
     */
    public static function loadFromFile($id, $filename = 'data/invoices.json') {
        if (!file_exists($filename)) {
            throw new Exception("Invoice file not found");
        }

        $contents = file_get_contents($filename);
        $invoices = json_decode($contents, true);

        // Handle both single invoice and array of invoices
        // (since saveToFile is broken and only saves one)
        if (isset($invoices['id'])) {
            $invoices = [$invoices];
        }

        foreach ($invoices as $invoiceData) {
            if ($invoiceData['id'] == $id) {
                $invoice = new Invoice($invoiceData['customer']);
                $invoice->id = $invoiceData['id'];
                $invoice->discount = $invoiceData['discount'];

                foreach ($invoiceData['items'] as $item) {
                    // This might break because of the qty/quantity issue
                    $qty = isset($item['quantity']) ? $item['quantity'] : $item['qty'];
                    $invoice->addItem($item['name'], $item['price'], $qty);
                }

                return $invoice;
            }
        }

        throw new Exception("Invoice not found: " . $id);
    }
}
