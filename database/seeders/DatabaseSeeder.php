<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\AssetType;
use App\Models\Asset;
use App\Models\Loan;
use App\Models\Inspection;
use App\Enums\AssetStatus;
use App\Enums\Condition;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Kamel Deeb',
            'email' => 'admin@hope.com',
            'password' => bcrypt('password'),
        ]);

        // Create branches
        $damascus = Branch::create(['name' => 'Damascus Headquarters']);
        $aleppo = Branch::create(['name' => 'Aleppo Branch']);
        $homs = Branch::create(['name' => 'Homs Branch']);

        // Create departments
        $itDept = Department::create(['name' => 'IT Department', 'branch_id' => $damascus->id]);
        $hrDept = Department::create(['name' => 'Human Resources', 'branch_id' => $damascus->id]);
        $financeDept = Department::create(['name' => 'Finance', 'branch_id' => $aleppo->id]);
        $operationsDept = Department::create(['name' => 'Operations', 'branch_id' => $homs->id]);

        // Create asset types
        $laptopType = AssetType::create(['name' => 'Laptop']);
        $printerType = AssetType::create(['name' => 'Printer']);
        $monitorType = AssetType::create(['name' => 'Monitor']);
        $tabletType = AssetType::create(['name' => 'Tablet']);

        // Create 15 employees
        $employees = [];
        $employeeData = [
            ['name' => 'Kamel Deeb', 'email' => 'kamel@hope.com', 'dept' => $itDept],
            ['name' => 'Nirvana', 'email' => 'nirvana@hope.com', 'dept' => $itDept],
            ['name' => 'Samer Al-Rashid', 'email' => 'samer@hope.com', 'dept' => $hrDept],
            ['name' => 'Jomana Al-Salem', 'email' => 'jomana@hope.com', 'dept' => $financeDept],
            ['name' => 'Fadi Al-Khoury', 'email' => 'fadi@hope.com', 'dept' => $operationsDept],
            ['name' => 'Layla Habib', 'email' => 'layla@hope.com', 'dept' => $itDept],
            ['name' => 'Johnny Haddad', 'email' => 'johnny@hope.com', 'dept' => $financeDept],
            ['name' => 'Sara Jamal', 'email' => 'sara@hope.com', 'dept' => $operationsDept],
            ['name' => 'Rana Al-Sayed', 'email' => 'rana@hope.com', 'dept' => $itDept],
            ['name' => 'Nour Issa', 'email' => 'nour@hope.com', 'dept' => $operationsDept],
            ['name' => 'Elias Mansour', 'email' => 'elias@hope.com', 'dept' => $hrDept],
            ['name' => 'Maya Zidan', 'email' => 'maya@hope.com', 'dept' => $financeDept],
            ['name' => 'George Hanna', 'email' => 'george@hope.com', 'dept' => $operationsDept],
            ['name' => 'Zina Haddad', 'email' => 'zina@hope.com', 'dept' => $itDept],
            ['name' => 'Tarek Saloum', 'email' => 'tarek@hope.com', 'dept' => $financeDept],

        ];

        foreach ($employeeData as $data) {
            $employees[] = Employee::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'department_id' => $data['dept']->id,
            ]);
        }

        // Create 30 assets
        $assets = [];
        $assetData = [
            ['type' => $laptopType, 'serial' => 'LAP001', 'date' => now()->subYears(2)],
            ['type' => $laptopType, 'serial' => 'LAP002', 'date' => now()->subMonths(6)],
            ['type' => $laptopType, 'serial' => 'LAP003', 'date' => now()->subMonths(3)],
            ['type' => $laptopType, 'serial' => 'LAP004', 'date' => now()->subYears(1)],
            ['type' => $laptopType, 'serial' => 'LAP005', 'date' => now()->subMonths(1)],
            ['type' => $printerType, 'serial' => 'PRT001', 'date' => now()->subYears(3)],
            ['type' => $printerType, 'serial' => 'PRT002', 'date' => now()->subMonths(8)],
            ['type' => $printerType, 'serial' => 'PRT003', 'date' => now()->subMonths(4)],
            ['type' => $printerType, 'serial' => 'PRT004', 'date' => now()->subYears(1)],
            ['type' => $printerType, 'serial' => 'PRT005', 'date' => now()->subMonths(2)],
            ['type' => $monitorType, 'serial' => 'MON001', 'date' => now()->subYears(2)],
            ['type' => $monitorType, 'serial' => 'MON002', 'date' => now()->subMonths(7)],
            ['type' => $monitorType, 'serial' => 'MON003', 'date' => now()->subMonths(5)],
            ['type' => $monitorType, 'serial' => 'MON004', 'date' => now()->subYears(1)],
            ['type' => $monitorType, 'serial' => 'MON005', 'date' => now()->subMonths(3)],
            ['type' => $tabletType, 'serial' => 'TAB001', 'date' => now()->subYears(1)],
            ['type' => $tabletType, 'serial' => 'TAB002', 'date' => now()->subMonths(9)],
            ['type' => $tabletType, 'serial' => 'TAB003', 'date' => now()->subMonths(6)],
            ['type' => $tabletType, 'serial' => 'TAB004', 'date' => now()->subYears(2)],
            ['type' => $tabletType, 'serial' => 'TAB005', 'date' => now()->subMonths(4)],
            ['type' => $laptopType, 'serial' => 'LAP006', 'date' => now()->subYears(3)],
            ['type' => $laptopType, 'serial' => 'LAP007', 'date' => now()->subMonths(10)],
            ['type' => $printerType, 'serial' => 'PRT006', 'date' => now()->subYears(2)],
            ['type' => $monitorType, 'serial' => 'MON006', 'date' => now()->subMonths(11)],
            ['type' => $tabletType, 'serial' => 'TAB006', 'date' => now()->subYears(1)],
            ['type' => $laptopType, 'serial' => 'LAP008', 'date' => now()->subMonths(12)],
            ['type' => $printerType, 'serial' => 'PRT007', 'date' => now()->subYears(4)],
            ['type' => $monitorType, 'serial' => 'MON007', 'date' => now()->subMonths(13)],
            ['type' => $tabletType, 'serial' => 'TAB007', 'date' => now()->subYears(2)],
            ['type' => $laptopType, 'serial' => 'LAP009', 'date' => now()->subMonths(14)],
        ];

        foreach ($assetData as $data) {
            $assets[] = Asset::create([
                'asset_type_id' => $data['type']->id,
                'serial_number' => $data['serial'],
                'purchase_date' => $data['date'],
                'status' => AssetStatus::AVAILABLE,
            ]);
        }

        // Create 20 loans with varying dates
        $loanData = [
            ['employee' => $employees[0], 'asset' => $assets[0], 'borrowed' => now()->subMonths(8), 'returned' => now()->subMonths(7), 'checkout' => Condition::EXCELLENT, 'return' => Condition::GOOD],
            ['employee' => $employees[1], 'asset' => $assets[1], 'borrowed' => now()->subMonths(5), 'returned' => now()->subMonths(4), 'checkout' => Condition::GOOD, 'return' => Condition::GOOD],
            ['employee' => $employees[2], 'asset' => $assets[2], 'borrowed' => now()->subMonths(2), 'returned' => now()->subMonths(1), 'checkout' => Condition::EXCELLENT, 'return' => Condition::EXCELLENT],
            ['employee' => $employees[3], 'asset' => $assets[3], 'borrowed' => now()->subMonths(10), 'returned' => now()->subMonths(9), 'checkout' => Condition::FAIR, 'return' => Condition::NEEDS_REPAIR],
            ['employee' => $employees[4], 'asset' => $assets[4], 'borrowed' => now()->subMonths(1), 'returned' => null, 'checkout' => Condition::EXCELLENT, 'return' => null],
            ['employee' => $employees[5], 'asset' => $assets[5], 'borrowed' => now()->subMonths(15), 'returned' => now()->subMonths(14), 'checkout' => Condition::GOOD, 'return' => Condition::FAIR],
            ['employee' => $employees[6], 'asset' => $assets[6], 'borrowed' => now()->subMonths(7), 'returned' => now()->subMonths(6), 'checkout' => Condition::EXCELLENT, 'return' => Condition::GOOD],
            ['employee' => $employees[7], 'asset' => $assets[7], 'borrowed' => now()->subMonths(3), 'returned' => now()->subMonths(2), 'checkout' => Condition::GOOD, 'return' => Condition::GOOD],
            ['employee' => $employees[8], 'asset' => $assets[8], 'borrowed' => now()->subMonths(12), 'returned' => now()->subMonths(11), 'checkout' => Condition::FAIR, 'return' => Condition::NEEDS_REPAIR],
            ['employee' => $employees[9], 'asset' => $assets[9], 'borrowed' => now()->subMonths(4), 'returned' => null, 'checkout' => Condition::EXCELLENT, 'return' => null],
            ['employee' => $employees[0], 'asset' => $assets[10], 'borrowed' => now()->subMonths(6), 'returned' => now()->subMonths(5), 'checkout' => Condition::EXCELLENT, 'return' => Condition::EXCELLENT],
            ['employee' => $employees[1], 'asset' => $assets[11], 'borrowed' => now()->subMonths(9), 'returned' => now()->subMonths(8), 'checkout' => Condition::GOOD, 'return' => Condition::GOOD],
            ['employee' => $employees[2], 'asset' => $assets[12], 'borrowed' => now()->subMonths(11), 'returned' => now()->subMonths(10), 'checkout' => Condition::FAIR, 'return' => Condition::FAIR],
            ['employee' => $employees[3], 'asset' => $assets[13], 'borrowed' => now()->subMonths(13), 'returned' => now()->subMonths(12), 'checkout' => Condition::NEEDS_REPAIR, 'return' => Condition::NEEDS_REPAIR],
            ['employee' => $employees[4], 'asset' => $assets[14], 'borrowed' => now()->subMonths(2), 'returned' => null, 'checkout' => Condition::EXCELLENT, 'return' => null],
            ['employee' => $employees[5], 'asset' => $assets[15], 'borrowed' => now()->subMonths(16), 'returned' => now()->subMonths(15), 'checkout' => Condition::GOOD, 'return' => Condition::FAIR],
            ['employee' => $employees[6], 'asset' => $assets[16], 'borrowed' => now()->subMonths(8), 'returned' => now()->subMonths(7), 'checkout' => Condition::EXCELLENT, 'return' => Condition::GOOD],
            ['employee' => $employees[7], 'asset' => $assets[17], 'borrowed' => now()->subMonths(5), 'returned' => null, 'checkout' => Condition::GOOD, 'return' => null],
            ['employee' => $employees[8], 'asset' => $assets[18], 'borrowed' => now()->subMonths(14), 'returned' => now()->subMonths(13), 'checkout' => Condition::FAIR, 'return' => Condition::NEEDS_REPAIR],
            ['employee' => $employees[9], 'asset' => $assets[19], 'borrowed' => now()->subMonths(3), 'returned' => null, 'checkout' => Condition::EXCELLENT, 'return' => null],
        ];

        foreach ($loanData as $data) {
            Loan::create([
                'employee_id' => $data['employee']->id,
                'asset_id' => $data['asset']->id,
                'borrowed_at' => $data['borrowed'],
                'returned_at' => $data['returned'],
                'condition_at_checkout' => $data['checkout'],
                'condition_at_return' => $data['return'],
            ]);

            // Update asset status if not returned
            if ($data['returned'] === null) {
                $data['asset']->update(['status' => AssetStatus::BORROWED]);
            } else {
                $data['asset']->update(['status' => AssetStatus::AVAILABLE]);
            }
        }

        // Create inspections for some returned assets
        $returnedLoans = Loan::whereNotNull('returned_at')->get();
        $inspectionLoans = $returnedLoans->take(5); // First 5 returned loans

        foreach ($inspectionLoans as $loan) {
            $loan->asset->update(['status' => AssetStatus::UNDER_INSPECTION]);
            Inspection::create([
                'asset_id' => $loan->asset_id,
                'loan_id' => $loan->id,
                'inspected_by' => null,
            ]);
        }
    }
}