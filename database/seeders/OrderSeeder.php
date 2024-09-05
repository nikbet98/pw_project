<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $users = User::all(); // Recupera tutti gli utenti
        $products = Product::all(); // Recupera tutti i prodotti

        // Genera 50 ordini casuali
        for ($i = 0; $i < 50; $i++) {
            // Seleziona un utente casuale
            $user = $users->random();

            // Seleziona una data casuale nell'anno corrente
            $date = now()->subDays(rand(0, 365))->format('Y-m-d');

            // Crea un nuovo ordine
            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0, // Verrà aggiornato dopo aver aggiunto i prodotti
                'state' => 'completed', // Puoi personalizzare lo stato se necessario
                'date' => $date,
            ]);

            // Seleziona casualmente fino a 2 prodotti
            $selectedProducts = $products->random(rand(1, 2));

            $total = 0;

            foreach ($selectedProducts as $product) {
                // Calcola il prezzo scontato se c'è uno sconto
                $discount = rand(0, 100) / 100; // Sconto casuale tra 0% e 100%
                $price = $product->price * (1 - $discount);

                // Aggiorna il totale dell'ordine
                $total += $price;

                // Aggiungi il prodotto all'ordine con i dettagli
                $order->products()->attach($product->id, [
                    'quantity' => 1,
                    'price' => $product->price,
                    'discount' => $discount,
                ]);
            }

            // Aggiorna il totale dell'ordine
            $order->update(['total' => $total]);
        }
    }
}
