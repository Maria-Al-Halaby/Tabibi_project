<?php

namespace Database\Seeders;

use App\Models\Promot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */



    public function run(): void
    {
        $promots = [
    [ "information" => "Drinking enough water daily improves energy levels and brain function." ],
    [ "information" => "Walking 30 minutes a day reduces the risk of heart disease." ],
    [ "information" => "Adequate sleep boosts your immune system and memory." ],
    [ "information" => "Exposure to morning sunlight helps the body produce vitamin D." ],
    [ "information" => "Fruits and vegetables provide essential vitamins and prevent chronic diseases." ],
    [ "information" => "Eating meals at consistent times improves digestion and metabolism." ],
    [ "information" => "Smiling can reduce stress and improve your overall mood." ],
    [ "information" => "Green tea is rich in antioxidants and can boost metabolism." ],
    [ "information" => "Washing hands regularly helps prevent infections and diseases." ],
    [ "information" => "Quitting smoking improves heart and lung health almost immediately." ],
    [ "information" => "High-fiber foods support healthy digestion and bowel movements." ],
    [ "information" => "Regular physical activity improves blood circulation and brain health." ],
    [ "information" => "Laughing reduces stress hormones and boosts happiness chemicals." ],
    [ "information" => "Reducing sugar intake supports healthy weight and clearer skin." ],
    [ "information" => "Eating fish twice a week benefits heart and brain function." ],
    [ "information" => "Moderate coffee consumption may lower risk of some diseases." ],
    [ "information" => "Staying hydrated helps flush out toxins from the body." ],
    [ "information" => "Sleeping on your left side can improve digestion and reduce acid reflux." ],
    [ "information" => "A healthy breakfast helps regulate appetite throughout the day." ],
    [ "information" => "Excessive salt intake increases the risk of high blood pressure." ],
    [ "information" => "Deep breathing exercises reduce stress and strengthen the lungs." ],
    [ "information" => "Nuts provide healthy fats, fiber, and essential vitamins." ],
    [ "information" => "Writing daily can relieve anxiety and clear your mind." ],
    [ "information" => "Avoiding fried foods supports heart health and weight control." ],
    [ "information" => "Leafy greens are excellent for eye and bone health." ],
    [ "information" => "Routine medical checkups help detect diseases early." ],
    [ "information" => "Avoid sitting for long periods to protect your spine and posture." ],
    [ "information" => "Dark chocolate contains antioxidants that support heart health." ],
    [ "information" => "Fresh air can improve mood and respiratory health." ],
    [ "information" => "A balanced diet leads to healthier skin and stronger immunity." ],
    [ "information" => "Chewing food thoroughly promotes better digestion." ],
    [ "information" => "Yoga reduces stress and improves flexibility and balance." ],
    [ "information" => "Milk is a great source of calcium for strong bones and teeth." ],
    [ "information" => "Whole grains help regulate blood sugar levels." ],
    [ "information" => "Avoiding fast food reduces the risk of obesity and heart issues." ],
    [ "information" => "Apples are rich in antioxidants and support heart health." ],
    [ "information" => "Drinking enough water daily reduces the risk of kidney stones." ],
    [ "information" => "Limiting screen time before sleep improves sleep quality." ],
    [ "information" => "Healthy fats like olive oil support heart and brain health." ],
    [ "information" => "Maintaining good hygiene prevents viral and bacterial infections." ],
    [ "information" => "Vitamin C strengthens the immune system and speeds healing." ],
    [ "information" => "Proper posture protects the spine and reduces back pain." ],
    [ "information" => "Warm water with lemon in the morning aids digestion." ],
    [ "information" => "Too much caffeine can cause anxiety and sleep problems." ],
    [ "information" => "Iron-rich foods prevent anemia and boost oxygen levels in the body." ],
    [ "information" => "Regular exercise improves mental health and reduces depression." ],
    [ "information" => "Staying active reduces the risk of diabetes and obesity." ],
    [ "information" => "Probiotics support healthy gut bacteria and strong digestion." ],
    [ "information" => "Drinking herbal tea can help relax muscles and improve sleep." ],
    [ "information" => "Moderate sun exposure boosts mood and vitamin D production." ],
        ];

        foreach ($promots as $promot) {
            Promot::create($promot);
        }
    }
}
