<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Support\Facades\Schema;

class CategoriesAndExpensesSeeder extends Seeder
{
    public function run()
    {

        Schema::disableForeignKeyConstraints();

        Category::truncate();
        Expense::truncate();

        $categories = [
            ['name' => 'Alquiler', 'user_id' => 1],
            ['name' => 'Comida', 'user_id' => 1],
            ['name' => 'Transporte', 'user_id' => 1],
            ['name' => 'Entretenimiento', 'user_id' => 1],
            ['name' => 'Servicios', 'user_id' => 1],
            ['name' => 'Ropa', 'user_id' => 1],
            ['name' => 'Electrónicos', 'user_id' => 1],
            ['name' => 'Viajes', 'user_id' => 1],
            ['name' => 'Regalos', 'user_id' => 1],
            ['name' => 'Educación', 'user_id' => 1],
            ['name' => 'Salud', 'user_id' => 1],
            ['name' => 'Seguros', 'user_id' => 1],
            ['name' => 'Higiene', 'user_id' => 1],
            ['name' => 'Otros', 'user_id' => 1],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $expenses = [
            ['description' => 'Alquiler de enero 2025', 'amount' => 1000, 'category_id' => 1, 'date' => '2025-01-01', 'user_id' => 1],
            ['description' => 'Comida en el restaurante', 'amount' => 50, 'category_id' => 2, 'date' => '2025-01-05', 'user_id' => 1],
            ['description' => 'Gasolina para el coche', 'amount' => 20, 'category_id' => 3, 'date' => '2025-01-10', 'user_id' => 1],
            ['description' => 'Entradas para el cine', 'amount' => 30, 'category_id' => 4, 'date' => '2025-01-15', 'user_id' => 1],
            ['description' => 'Pago de servicios básicos', 'amount' => 150, 'category_id' => 5, 'date' => '2025-01-20', 'user_id' => 1],
            ['description' => 'Compra de ropa', 'amount' => 100, 'category_id' => 6, 'date' => '2025-01-22', 'user_id' => 1],
            ['description' => 'Compra de un nuevo teléfono', 'amount' => 500, 'category_id' => 7, 'date' => '2025-01-25', 'user_id' => 1],
            ['description' => 'Viaje a la playa', 'amount' => 200, 'category_id' => 8, 'date' => '2025-01-28', 'user_id' => 1],
            ['description' => 'Regalo de cumpleaños', 'amount' => 80, 'category_id' => 9, 'date' => '2025-01-30', 'user_id' => 1],
            ['description' => 'Curso de inglés', 'amount' => 300, 'category_id' => 10, 'date' => '2025-02-01', 'user_id' => 1],
            ['description' => 'Consulta médica', 'amount' => 40, 'category_id' => 11, 'date' => '2025-02-05', 'user_id' => 1],
            ['description' => 'Prima de seguro de vida', 'amount' => 120, 'category_id' => 12, 'date' => '2025-02-10', 'user_id' => 1],
            ['description' => 'Compra de productos de limpieza', 'amount' => 30, 'category_id' => 13, 'date' => '2025-02-12', 'user_id' => 1],
            ['description' => 'Gasto no categorizado', 'amount' => 20, 'category_id' => 14, 'date' => '2025-02-15', 'user_id' => 1],
            ['description' => 'Alquiler de febrero 2025', 'amount' => 1000, 'category_id' => 1, 'date' => '2025-02-01', 'user_id' => 1],
            ['description' => 'Comida en casa', 'amount' => 80, 'category_id' => 2, 'date' => '2025-02-05', 'user_id' => 1],
            ['description' => 'Combustible para el viaje', 'amount' => 50, 'category_id' => 3, 'date' => '2025-02-10', 'user_id' => 1],
            ['description' => 'Suscripción a un gimnasio', 'amount' => 60, 'category_id' => 4, 'date' => '2025-02-15', 'user_id' => 1],
            ['description' => 'Pago de servicios de internet', 'amount' => 30, 'category_id' => 5, 'date' => '2025-02-20', 'user_id' => 1],
        ];

        
        foreach ($expenses as $expense) {
            Expense::create($expense);
        }

        Schema::enableForeignKeyConstraints();
    }
}
