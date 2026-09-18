<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser   = User::where('email', 'demo@example.com')->first();
        $managerUser = User::where('email', 'manager@nexaerp.com')->first();

        // ── 1. CUSTOMERS ──────────────────────────────────────────
        $customers = [
            ['name' => 'Rahul Sharma',    'company' => 'TechSoft Solutions',    'email' => 'rahul@techsoft.in',    'phone' => '9812345670', 'city' => 'Mumbai',    'state' => 'Maharashtra', 'country' => 'India', 'status' => 'active',   'notes' => 'Key enterprise client. Prefers email communication.'],
            ['name' => 'Priya Mehta',     'company' => 'Mehta Enterprises',     'email' => 'priya@mehta.co.in',   'phone' => '9823456781', 'city' => 'Delhi',     'state' => 'Delhi',       'country' => 'India', 'status' => 'active',   'notes' => 'Interested in bulk orders.'],
            ['name' => 'Arjun Patel',     'company' => 'Patel & Co',            'email' => 'arjun@patelnco.com',  'phone' => '9834567892', 'city' => 'Ahmedabad', 'state' => 'Gujarat',     'country' => 'India', 'status' => 'active',   'notes' => 'Long term client since 2022.'],
            ['name' => 'Sneha Reddy',     'company' => 'Reddy Industries',      'email' => 'sneha@reddyind.com',  'phone' => '9845678903', 'city' => 'Hyderabad', 'state' => 'Telangana',   'country' => 'India', 'status' => 'active',   'notes' => 'Prefers monthly billing cycle.'],
            ['name' => 'Vikram Singh',    'company' => 'Singh Traders',         'email' => 'vikram@singht.in',    'phone' => '9856789014', 'city' => 'Jaipur',    'state' => 'Rajasthan',   'country' => 'India', 'status' => 'active',   'notes' => 'Wholesale buyer.'],
            ['name' => 'Ananya Iyer',     'company' => 'Iyer Tech Park',        'email' => 'ananya@iyertech.com', 'phone' => '9867890125', 'city' => 'Chennai',   'state' => 'Tamil Nadu',  'country' => 'India', 'status' => 'active',   'notes' => 'IT solutions client.'],
            ['name' => 'Rohit Gupta',     'company' => 'Gupta Electronics',     'email' => 'rohit@guptaelec.com', 'phone' => '9878901236', 'city' => 'Pune',      'state' => 'Maharashtra', 'country' => 'India', 'status' => 'active',   'notes' => 'Electronics distributor.'],
            ['name' => 'Kavya Nair',      'company' => 'Nair Retail Group',     'email' => 'kavya@nairretail.in', 'phone' => '9889012347', 'city' => 'Kochi',     'state' => 'Kerala',      'country' => 'India', 'status' => 'inactive', 'notes' => 'Account on hold pending review.'],
            ['name' => 'Suresh Kumar',    'company' => 'Kumar Constructions',   'email' => 'suresh@kumarconstruction.com', 'phone' => '9890123458', 'city' => 'Bengaluru', 'state' => 'Karnataka', 'country' => 'India', 'status' => 'active', 'notes' => 'Construction materials buyer.'],
            ['name' => 'Deepika Joshi',   'company' => 'Joshi Pharmaceuticals', 'email' => 'deepika@joshipharma.com', 'phone' => '9901234569', 'city' => 'Nagpur', 'state' => 'Maharashtra', 'country' => 'India', 'status' => 'active', 'notes' => 'Pharma wholesale client.'],
            ['name' => 'Manish Agarwal',  'company' => 'Agarwal Textiles',      'email' => 'manish@agarwaltex.com', 'phone' => '9712345670', 'city' => 'Surat',   'state' => 'Gujarat',     'country' => 'India', 'status' => 'active',   'notes' => 'Textile exporter.'],
            ['name' => 'Pooja Verma',     'company' => 'Verma Food Products',   'email' => 'pooja@vermafood.in',  'phone' => '9723456781', 'city' => 'Lucknow',   'state' => 'Uttar Pradesh','country' => 'India', 'status' => 'active',  'notes' => 'Food industry supplier.'],
        ];

        $createdCustomers = [];
        foreach ($customers as $data) {
            $createdCustomers[] = Customer::create(array_merge($data, [
                'created_by' => $adminUser->id,
                'address'    => '123, Business District',
            ]));
        }

        // ── 2. LEADS ──────────────────────────────────────────────
        $leads = [
            ['name' => 'Nikhil Bansal',   'company' => 'Bansal Tech',       'email' => 'nikhil@bansaltech.com',  'phone' => '9611111111', 'source' => 'Website',       'status' => 'new',       'expected_value' => 75000,  'follow_up_date' => now()->addDays(2)->format('Y-m-d'),  'assigned_to' => $managerUser->id, 'notes' => 'Interested in annual contract.'],
            ['name' => 'Ritu Kapoor',     'company' => 'Kapoor Media',      'email' => 'ritu@kapoorhedia.com',   'phone' => '9622222222', 'source' => 'Referral',      'status' => 'contacted', 'expected_value' => 120000, 'follow_up_date' => now()->addDays(5)->format('Y-m-d'),  'assigned_to' => $adminUser->id,   'notes' => 'Follow up after product demo.'],
            ['name' => 'Sameer Khan',     'company' => 'Khan Logistics',    'email' => 'sameer@khanlogistics.in','phone' => '9633333333', 'source' => 'Cold Call',     'status' => 'qualified', 'expected_value' => 250000, 'follow_up_date' => now()->addDays(3)->format('Y-m-d'),  'assigned_to' => $managerUser->id, 'notes' => 'Needs logistics software solution.'],
            ['name' => 'Tanya Malhotra',  'company' => 'Malhotra Exports',  'email' => 'tanya@malhotraexp.com',  'phone' => '9644444444', 'source' => 'Trade Show',    'status' => 'proposal',  'expected_value' => 180000, 'follow_up_date' => now()->addDays(1)->format('Y-m-d'),  'assigned_to' => $adminUser->id,   'notes' => 'Proposal sent. Waiting for approval.'],
            ['name' => 'Vivek Sharma',    'company' => 'Sharma Chemicals',  'email' => 'vivek@sharmachem.com',   'phone' => '9655555555', 'source' => 'Social Media',  'status' => 'won',       'expected_value' => 95000,  'follow_up_date' => null,                                'assigned_to' => $managerUser->id, 'notes' => 'Deal closed. Move to customer.'],
            ['name' => 'Geeta Pillai',    'company' => 'Pillai Services',   'email' => 'geeta@pillaiservices.in','phone' => '9666666666', 'source' => 'Email Campaign','status' => 'lost',      'expected_value' => 60000,  'follow_up_date' => null,                                'assigned_to' => $adminUser->id,   'notes' => 'Went with competitor. Budget constraint.'],
            ['name' => 'Harish Menon',    'company' => 'Menon Associates',  'email' => 'harish@menonassoc.com',  'phone' => '9677777777', 'source' => 'Referral',      'status' => 'new',       'expected_value' => 45000,  'follow_up_date' => now()->addDays(7)->format('Y-m-d'),  'assigned_to' => $managerUser->id, 'notes' => 'New referral from Arjun Patel.'],
            ['name' => 'Sunita Rao',      'company' => 'Rao Technologies',  'email' => 'sunita@raotech.com',     'phone' => '9688888888', 'source' => 'Website',       'status' => 'contacted', 'expected_value' => 320000, 'follow_up_date' => now()->addDays(4)->format('Y-m-d'),  'assigned_to' => $adminUser->id,   'notes' => 'Enterprise plan inquiry.'],
            ['name' => 'Akash Trivedi',   'company' => 'Trivedi Motors',    'email' => 'akash@trivedimotors.in', 'phone' => '9699999999', 'source' => 'Cold Call',     'status' => 'qualified', 'expected_value' => 150000, 'follow_up_date' => now()->addDays(6)->format('Y-m-d'),  'assigned_to' => $managerUser->id, 'notes' => 'Needs ERP for dealership management.'],
            ['name' => 'Meera Ghosh',     'company' => 'Ghosh Retail',      'email' => 'meera@ghoshretail.com',  'phone' => '9600000000', 'source' => 'Trade Show',    'status' => 'proposal',  'expected_value' => 88000,  'follow_up_date' => now()->subDays(1)->format('Y-m-d'),  'assigned_to' => $adminUser->id,   'notes' => 'Overdue follow-up. Contact today.'],
        ];

        $createdLeads = [];
        foreach ($leads as $data) {
            $createdLeads[] = Lead::create(array_merge($data, [
                'created_by' => $adminUser->id,
            ]));
        }

        // ── 3. ACTIVITIES ─────────────────────────────────────────
        $activityData = [
            // Customer activities
            ['subject' => $createdCustomers[0], 'type' => 'call',      'desc' => 'Discussed Q4 requirements. Client needs 50 units by end of month.', 'days' => -1],
            ['subject' => $createdCustomers[0], 'type' => 'email',     'desc' => 'Sent product catalog and pricing sheet.', 'days' => -3],
            ['subject' => $createdCustomers[1], 'type' => 'meeting',   'desc' => 'In-person meeting at client office. Reviewed contract terms.', 'days' => -2],
            ['subject' => $createdCustomers[2], 'type' => 'follow_up', 'desc' => 'Followed up on pending invoice. Client confirmed payment by Friday.', 'days' => -1],
            ['subject' => $createdCustomers[3], 'type' => 'call',      'desc' => 'Resolved delivery issue. New shipment scheduled.', 'days' => -5],
            ['subject' => $createdCustomers[4], 'type' => 'note',      'desc' => 'Client prefers WhatsApp for quick communication.', 'days' => -7],
            // Lead activities
            ['subject' => $createdLeads[0],     'type' => 'call',      'desc' => 'Initial discovery call. Explained our ERP modules.', 'days' => -2],
            ['subject' => $createdLeads[1],     'type' => 'email',     'desc' => 'Sent proposal document and case studies.', 'days' => -1],
            ['subject' => $createdLeads[2],     'type' => 'meeting',   'desc' => 'Online demo conducted. Client impressed with inventory module.', 'days' => -3],
            ['subject' => $createdLeads[3],     'type' => 'follow_up', 'desc' => 'Followed up on proposal. Decision expected next week.', 'days' => -1],
            ['subject' => $createdLeads[6],     'type' => 'call',      'desc' => 'First contact call. Scheduled demo for next week.', 'days' => 0],
        ];

        foreach ($activityData as $act) {
            Activity::create([
                'user_id'       => $adminUser->id,
                'subject_type'  => get_class($act['subject']),
                'subject_id'    => $act['subject']->id,
                'type'          => $act['type'],
                'activity_date' => now()->addDays($act['days']),
                'description'   => $act['desc'],
            ]);
        }

        // ── 4. CATEGORIES ─────────────────────────────────────────
        $categories = [
            ['name' => 'Electronics',     'description' => 'Electronic devices and components'],
            ['name' => 'Office Supplies', 'description' => 'Stationery and office essentials'],
            ['name' => 'Software',        'description' => 'Software licenses and subscriptions'],
            ['name' => 'Furniture',       'description' => 'Office and business furniture'],
            ['name' => 'Networking',      'description' => 'Network equipment and accessories'],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[] = Category::create($cat);
        }

        // ── 5. PRODUCTS ───────────────────────────────────────────
        $products = [
            // Electronics
            ['name' => 'Laptop Pro 15"',         'sku' => 'ELEC-001', 'category' => 0, 'purchase' => 55000, 'selling' => 72000, 'stock' => 25, 'min' => 5,  'status' => 'active'],
            ['name' => 'Desktop Workstation',     'sku' => 'ELEC-002', 'category' => 0, 'purchase' => 42000, 'selling' => 58000, 'stock' => 12, 'min' => 3,  'status' => 'active'],
            ['name' => 'LED Monitor 27"',         'sku' => 'ELEC-003', 'category' => 0, 'purchase' => 18000, 'selling' => 25000, 'stock' => 3,  'min' => 5,  'status' => 'active'],  // low stock
            ['name' => 'Mechanical Keyboard',     'sku' => 'ELEC-004', 'category' => 0, 'purchase' => 3500,  'selling' => 5500,  'stock' => 40, 'min' => 10, 'status' => 'active'],
            ['name' => 'Wireless Mouse',          'sku' => 'ELEC-005', 'category' => 0, 'purchase' => 1200,  'selling' => 1999,  'stock' => 2,  'min' => 10, 'status' => 'active'],  // low stock
            // Office Supplies
            ['name' => 'A4 Paper Ream (500)',     'sku' => 'OFFC-001', 'category' => 1, 'purchase' => 280,   'selling' => 420,   'stock' => 150,'min' => 20, 'status' => 'active'],
            ['name' => 'Ballpoint Pen Box (50)',  'sku' => 'OFFC-002', 'category' => 1, 'purchase' => 180,   'selling' => 280,   'stock' => 80, 'min' => 15, 'status' => 'active'],
            ['name' => 'File Folders (Pack 10)',  'sku' => 'OFFC-003', 'category' => 1, 'purchase' => 120,   'selling' => 199,   'stock' => 4,  'min' => 10, 'status' => 'active'],  // low stock
            // Software
            ['name' => 'MS Office License',      'sku' => 'SOFT-001', 'category' => 2, 'purchase' => 8500,  'selling' => 12000, 'stock' => 50, 'min' => 5,  'status' => 'active'],
            ['name' => 'Antivirus 1 Year',       'sku' => 'SOFT-002', 'category' => 2, 'purchase' => 1200,  'selling' => 1999,  'stock' => 100,'min' => 10, 'status' => 'active'],
            // Networking
            ['name' => 'WiFi Router AC1200',     'sku' => 'NET-001',  'category' => 4, 'purchase' => 2800,  'selling' => 4200,  'stock' => 18, 'min' => 5,  'status' => 'active'],
            ['name' => 'Network Switch 8-Port',  'sku' => 'NET-002',  'category' => 4, 'purchase' => 3500,  'selling' => 5500,  'stock' => 1,  'min' => 3,  'status' => 'active'],  // low stock
            // Furniture
            ['name' => 'Ergonomic Office Chair', 'sku' => 'FURN-001', 'category' => 3, 'purchase' => 8500,  'selling' => 13500, 'stock' => 8,  'min' => 2,  'status' => 'active'],
            ['name' => 'Standing Desk',          'sku' => 'FURN-002', 'category' => 3, 'purchase' => 15000, 'selling' => 22000, 'stock' => 5,  'min' => 2,  'status' => 'active'],
        ];

        $createdProducts = [];
        foreach ($products as $p) {
            $createdProducts[] = Product::create([
                'name'           => $p['name'],
                'sku'            => $p['sku'],
                'category_id'    => $createdCategories[$p['category']]->id,
                'purchase_price' => $p['purchase'],
                'selling_price'  => $p['selling'],
                'stock_quantity' => $p['stock'],
                'min_stock_level'=> $p['min'],
                'status'         => $p['status'],
                'description'    => 'Quality product — ' . $p['name'],
            ]);
        }

        // ── 6. INVOICES + INVOICE ITEMS + PAYMENTS ────────────────
        $invoiceData = [
            [
                'customer'  => $createdCustomers[0],
                'date'      => now()->subDays(30),
                'due'       => now()->subDays(15),
                'status'    => 'paid',
                'items'     => [
                    ['product' => $createdProducts[0], 'qty' => 2, 'price' => 72000],
                    ['product' => $createdProducts[3], 'qty' => 2, 'price' => 5500],
                ],
                'tax'       => 18,
                'discount'  => 5,
                'paid_full' => true,
            ],
            [
                'customer'  => $createdCustomers[1],
                'date'      => now()->subDays(20),
                'due'       => now()->addDays(10),
                'status'    => 'partial',
                'items'     => [
                    ['product' => $createdProducts[1], 'qty' => 1, 'price' => 58000],
                    ['product' => $createdProducts[10],'qty' => 2, 'price' => 4200],
                ],
                'tax'       => 18,
                'discount'  => 0,
                'paid_full' => false,
                'paid_amt'  => 40000,
            ],
            [
                'customer'  => $createdCustomers[2],
                'date'      => now()->subDays(15),
                'due'       => now()->addDays(15),
                'status'    => 'sent',
                'items'     => [
                    ['product' => $createdProducts[5], 'qty' => 10, 'price' => 420],
                    ['product' => $createdProducts[6], 'qty' => 5,  'price' => 280],
                    ['product' => $createdProducts[8], 'qty' => 3,  'price' => 12000],
                ],
                'tax'       => 18,
                'discount'  => 0,
                'paid_full' => false,
                'paid_amt'  => 0,
            ],
            [
                'customer'  => $createdCustomers[3],
                'date'      => now()->subDays(45),
                'due'       => now()->subDays(15),
                'status'    => 'overdue',
                'items'     => [
                    ['product' => $createdProducts[2], 'qty' => 2, 'price' => 25000],
                    ['product' => $createdProducts[4], 'qty' => 4, 'price' => 1999],
                ],
                'tax'       => 18,
                'discount'  => 0,
                'paid_full' => false,
                'paid_amt'  => 0,
            ],
            [
                'customer'  => $createdCustomers[4],
                'date'      => now()->subDays(5),
                'due'       => now()->addDays(25),
                'status'    => 'paid',
                'items'     => [
                    ['product' => $createdProducts[12],'qty' => 3, 'price' => 13500],
                    ['product' => $createdProducts[13],'qty' => 2, 'price' => 22000],
                ],
                'tax'       => 18,
                'discount'  => 10,
                'paid_full' => true,
            ],
            [
                'customer'  => $createdCustomers[5],
                'date'      => now()->subDays(60),
                'due'       => now()->subDays(30),
                'status'    => 'paid',
                'items'     => [
                    ['product' => $createdProducts[9], 'qty' => 5,  'price' => 1999],
                    ['product' => $createdProducts[8], 'qty' => 2,  'price' => 12000],
                ],
                'tax'       => 18,
                'discount'  => 0,
                'paid_full' => true,
            ],
            [
                'customer'  => $createdCustomers[6],
                'date'      => now()->subDays(3),
                'due'       => now()->addDays(27),
                'status'    => 'draft',
                'items'     => [
                    ['product' => $createdProducts[0], 'qty' => 1,  'price' => 72000],
                ],
                'tax'       => 18,
                'discount'  => 0,
                'paid_full' => false,
                'paid_amt'  => 0,
            ],
        ];

        foreach ($invoiceData as $index => $inv) {
            // Calculate totals
            $subtotal = 0;
            foreach ($inv['items'] as $item) {
                $subtotal += $item['qty'] * $item['price'];
            }

            $discountAmt = round($subtotal * $inv['discount'] / 100, 2);
            $taxable     = $subtotal - $discountAmt;
            $taxAmt      = round($taxable * $inv['tax'] / 100, 2);
            $grandTotal  = $taxable + $taxAmt;
            $paidAmt     = $inv['paid_full'] ? $grandTotal : ($inv['paid_amt'] ?? 0);
            $remaining   = $grandTotal - $paidAmt;

            $invoice = Invoice::create([
                'customer_id'      => $inv['customer']->id,
                'created_by'       => $adminUser->id,
                'invoice_number'   => 'INV-' . now()->year . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'invoice_date'     => $inv['date'],
                'due_date'         => $inv['due'],
                'status'           => $inv['status'],
                'subtotal'         => $subtotal,
                'tax_percent'      => $inv['tax'],
                'tax_amount'       => $taxAmt,
                'discount_percent' => $inv['discount'],
                'discount_amount'  => $discountAmt,
                'grand_total'      => $grandTotal,
                'paid_amount'      => $paidAmt,
                'remaining_amount' => $remaining,
                'notes'            => 'Thank you for your business!',
            ]);

            // Create invoice items
            foreach ($inv['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id'       => $invoice->id,
                    'product_id'       => $item['product']->id,
                    'quantity'         => $item['qty'],
                    'unit_price'       => $item['price'],
                    'discount_percent' => 0,
                    'total'            => $item['qty'] * $item['price'],
                ]);
            }

            // Create payment if paid
            if ($paidAmt > 0) {
                Payment::create([
                    'invoice_id'      => $invoice->id,
                    'customer_id'     => $inv['customer']->id,
                    'created_by'      => $adminUser->id,
                    'amount'          => $paidAmt,
                    'payment_date'    => $inv['date']->copy()->addDays(rand(1, 5)),
                    'payment_method'  => collect(['cash','bank_transfer','upi','card'])->random(),
                    'reference_number'=> 'PAY' . strtoupper(uniqid()),
                    'notes'           => 'Payment received.',
                ]);
            }
        }

        $this->command->info('✅ Demo data seeded successfully!');
        $this->command->info('   → 12 Customers');
        $this->command->info('   → 10 Leads');
        $this->command->info('   → 11 Activities');
        $this->command->info('   → 5 Categories');
        $this->command->info('   → 14 Products (4 low stock)');
        $this->command->info('   → 7 Invoices');
        $this->command->info('   → Payments for paid invoices');

                // ── 7. STOCK TRANSACTIONS (additional demo) ───────────────
        $stockTxnData = [
            ['product' => $createdProducts[0],  'type' => 'stock_in',   'qty' => 10, 'notes' => 'Received from supplier'],
            ['product' => $createdProducts[1],  'type' => 'stock_in',   'qty' => 5,  'notes' => 'New stock arrival'],
            ['product' => $createdProducts[2],  'type' => 'stock_out',  'qty' => 3,  'notes' => 'Sold to customer'],
            ['product' => $createdProducts[3],  'type' => 'stock_in',   'qty' => 20, 'notes' => 'Bulk purchase'],
            ['product' => $createdProducts[4],  'type' => 'stock_out',  'qty' => 5,  'notes' => 'Customer order'],
            ['product' => $createdProducts[5],  'type' => 'stock_in',   'qty' => 50, 'notes' => 'Monthly restock'],
            ['product' => $createdProducts[7],  'type' => 'stock_out',  'qty' => 3,  'notes' => 'Office use'],
            ['product' => $createdProducts[10], 'type' => 'stock_in',   'qty' => 8,  'notes' => 'Supplier delivery'],
            ['product' => $createdProducts[11], 'type' => 'adjustment', 'qty' => 2,  'notes' => 'Physical count adjustment'],
            ['product' => $createdProducts[13], 'type' => 'stock_out',  'qty' => 1,  'notes' => 'Sold to Vikram Singh'],
        ];

        foreach ($stockTxnData as $txn) {
            $before = $txn['product']->stock_quantity;
            $after  = match($txn['type']) {
                'stock_in'   => $before + $txn['qty'],
                'stock_out'  => max(0, $before - $txn['qty']),
                'adjustment' => $txn['qty'],
            };

            \App\Models\StockTransaction::create([
                'product_id'      => $txn['product']->id,
                'user_id'         => $adminUser->id,
                'type'            => $txn['type'],
                'quantity'        => $txn['qty'],
                'quantity_before' => $before,
                'quantity_after'  => $after,
                'notes'           => $txn['notes'],
            ]);
        }

        $this->command->info('   → 10 Stock Transactions');
    }
}