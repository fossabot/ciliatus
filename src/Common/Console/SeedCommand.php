<?php

namespace Ciliatus\Common\Console;

use Ciliatus\Automation\Enum\ApplianceStateEnum;
use Ciliatus\Automation\Models\Appliance;
use Ciliatus\Automation\Models\ApplianceGroup;
use Ciliatus\Automation\Models\ApplianceTypeState;
use Ciliatus\Automation\Models\Capability;
use Ciliatus\Automation\Models\WorkflowAction;
use Ciliatus\Core\Models\Animal;
use Ciliatus\Core\Models\Habitat;
use Ciliatus\Core\Models\Location;
use Ciliatus\Monitoring\Enum\LogicalSensorStateEnum;
use Ciliatus\Monitoring\Models\PhysicalSensor;
use Exception;
use Faker\Factory;
use Illuminate\Console\Command;

class SeedCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'ciliatus:seed {--locations=3} {--habitats=15} {--animals=25}';

    /**
     * @var string
     */
    protected $description = 'Seed test data';

    /**
     * @throws Exception
     */
    public function handle()
    {

        $locations = (int)$this->option('locations');
        echo "Seeding " . $locations . " locations ..." . PHP_EOL;
        $faker = Factory::create();
        for ($i = 0; $i < $locations; $i++) {
            Location::create([
                'name' => $faker->unique()->address,
                'location_type_id' => random_int(1, 3)
            ]);
        }

        $habitats = (int)$this->option('habitats');
        echo "Seeding " . $habitats . " habitats ..." . PHP_EOL;
        $faker = Factory::create();
        for ($i = 0; $i < $habitats; $i++) {
            Habitat::create([
                'name' => $faker->unique()->city,
                'habitat_type_id' => random_int(1, 3),
                'location_id' => random_int(1, $locations),
                'width' => random_int(40, 120),
                'height' => random_int(80, 120),
                'depth' => random_int(50, 80)
            ]);
        }

        $animals = (int)$this->option('animals');
        echo "Seeding " . $animals . " animals ..." . PHP_EOL;
        $faker = Factory::create();
        for ($i = 0; $i < $animals; $i++) {
            Animal::create([
                'name' => $faker->unique()->name,
                'habitat_id' => random_int(1, $habitats),
                'animal_species_id' => random_int(1, 3)
            ]);
        }

        echo "Seeding physical sensors ..." . PHP_EOL;
        $faker = Factory::create();
        Habitat::get()->each(function(Habitat $habitat) use ($faker) {
            for ($i = 0; $i < random_int(1, 3); $i++) {
                $habitat->physical_sensors()->create([
                    'name' => $faker->unique()->mimeType,
                    'physical_sensor_type_id' => random_int(1, 2),
                    'position_x' => random_int(-($habitat->width/2) + 5, $habitat->width/2 - 5),
                    'position_y' => random_int(-($habitat->height/2) + 5, $habitat->height/2 - 5),
                    'position_z' => -$habitat->depth/2 + 5
                ]);
            }
        });
        Location::get()->each(function (Location $location) use ($faker) {
            for ($i = 0; $i < random_int(1, 3); $i++) {
                $location->physical_sensors()->create([
                    'name' => $faker->unique()->mimeType,
                    'physical_sensor_type_id' => random_int(1, 2)
                ]);
            }
        });

        echo "Seeding logical sensors ..." . PHP_EOL;
        $faker = Factory::create();
        $states = [
            LogicalSensorStateEnum::STATE_OK(),
            LogicalSensorStateEnum::STATE_NOTOK(),
            LogicalSensorStateEnum::STATE_ERROR(),
            LogicalSensorStateEnum::STATE_UNKNOWN()
        ];
        PhysicalSensor::get()->each(function(PhysicalSensor $physical_sensor) use ($faker, $states) {
            for ($i = 0; $i < random_int(2, 6); $i++) {
                $type = random_int(1, 2);
                $correction = random_int(-3, 3);
                $raw = $type == 0 ? random_int(18, 28) : random_int(0, 100);

                $physical_sensor->logical_sensors()->create([
                    'name' => $faker->unique()->mimeType,
                    'logical_sensor_type_id' => $type,
                    'reading_correction' => $correction,
                    'current_reading_raw' => $raw,
                    'current_reading_corrected' => $raw + $correction,
                    'state' => $states[random_int(0, 3)],
                    'state_text' => $faker->sentence(4),
                ]);
            }
        });

        echo "Seeding appliances ..." . PHP_EOL;
        $faker = Factory::create();
        $states = [
            ApplianceStateEnum::STATE_ACTIVE(),
            ApplianceStateEnum::STATE_INACTIVE(),
            ApplianceStateEnum::STATE_ERROR(),
            ApplianceStateEnum::STATE_UNKNOWN()
        ];
        Habitat::get()->each(function(Habitat $habitat) use ($faker, $states) {
            $groups = random_int(1, 2);
            for ($i = 0; $i < $groups; $i++) {
                 $habitat->appliance_groups()->create([
                    'name' => $faker->iban(),
                    'belongsToModel_type' => Habitat::class,
                    'belongsToModel_id' => $habitat->id
                ]);
            }

            $appliances = [];

            $habitat->appliance_groups->each(function(ApplianceGroup $group) use ($faker, $states, $habitat, &$appliances) {
                if (!$group->is_builtin) {
                    for ($j = 0; $j < random_int(1, 3); $j++) {
                        $group->capabilities()->attach(Capability::find(random_int(1, 6)));
                    }
                }

                for ($j = 0; $j < random_int(2, 5); $j++) {
                    $type = random_int(1, 6);
                    
                    $appliance = $group->appliances()->create([
                        'name' => $faker->swiftBicNumber,
                        'appliance_type_id' => $type,
                        'belongsToModel_type' => Habitat::class,
                        'belongsToModel_id' => $habitat->id,
                        'state' => $states[random_int(0, 3)],
                        'state_text' => $faker->sentence(4),
                        'maintenance_interval_days' => random_int(30, 180)
                    ]);

                    $appliance->setState($appliance->appliance_type->states[random_int(0, $appliance->appliance_type->states->count() - 1)])->save();
                    $appliances[] = $appliance;
                }
            });

            $workflow = $habitat->workflows()->create([
                'name' => $faker->email
            ]);

            $offset = 0;
            for ($i = 0; $i < random_int(2, count($appliances)); $i++) {
                $offset += random_int(0, 10);
                $workflow->actions()->create([
                    'name' => 'Activate ' . $appliances[$i]->name,
                    'appliance_type_state_id' => $appliances[$i]->appliance_type->states[0]->id,
                    'appliance_id' => $appliances[$i]->id,
                    'workflow_time_offset_seconds' => $offset,
                    'target_level' => random_int(10, 30),
                    'target_level_rampup_seconds' => random_int(0, 10)
                ]);
            }

            $offset += random_int(30, 60);
            $workflow->actions->each(function(WorkflowAction $action) use ($workflow, $offset) {
                $workflow->actions()->create([
                    'name' => 'Deactivate ' . $action->appliance->name,
                    'appliance_type_state_id' => $action->appliance->appliance_type->states[1]->id,
                    'appliance_id' => $action->appliance->id,
                    'workflow_time_offset_seconds' => $offset,
                    'target_level' => 0,
                    'target_level_rampup_seconds' => random_int(0, 10)
                ]);
            });
        });


        echo "Seeding done" . PHP_EOL;
    }

}