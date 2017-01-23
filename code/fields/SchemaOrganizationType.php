<?php
/**
 *
 *    SchemaOrganizationTypeField
 *
 *
 * @package    SeoHeroTool
 * @copyright  Copyright (c) 2017 Bastian Fritsch (http://www.bit-basti.de)
 * @license    BSD 2-clause license
 */
class SchemaOrganizationType extends DropdownField {

	protected $extraClasses = array('dropdown');

	public function __construct($name, $title = null, $source = null, $value = "", $form = null) {
		if (!is_array($source)) {
			$source = self::getSchemOrganizationTypes();
		}
		parent::__construct($name, ($title === null) ? $name : $title, $source, $value, $form);
	}

	public function Field($properties = array()) {
		$options = '';
		foreach ($this->getSource() as $value => $title) {
			if (is_array($title)) {
				$options .= "<optgroup label=\"$value\">";
				foreach ($title as $value2 => $title2) {
					$disabled = '';
					if (array_key_exists($value, $this->disabledItems)
						&& is_array($this->disabledItems[$value])
						&& in_array($value2, $this->disabledItems[$value])) {
						$disabled = 'disabled="disabled"';
					}
					$selected = $value2 == $this->value ? " selected=\"selected\"" : "";
					$options .= "<option$selected value=\"$value2\" $disabled>$title2</option>";
				}
				$options .= "</optgroup>";
			} else {
				// Fall back to the standard dropdown field
				$disabled = '';
				if (in_array($value, $this->disabledItems)) {
					$disabled = 'disabled="disabled"';
				}
				$selected = $value == $this->value ? " selected=\"selected\"" : "";
				$options .= "<option$selected value=\"$value\" $disabled>$title</option>";
			}
		}

		return FormField::create_tag('select', $this->getAttributes(), $options);
	}

	public function Type() {
		return 'groupeddropdown dropdown';
	}


  /*
  *  Function which lists all different Organization Types
   */
	public function getSchemOrganizationTypes() {
		return array(
			"Organization" => "Organization",
			"Corporation" => "Corporation",
			"GovernmentOrganization" => "Government Organization",
			"NGO" => "NGO",
			"EducationalOrganization" => array(
				"EducationalOrganization" => "Educational Organization",
				"CollegeOrUniversity" => "&mdash; College or University",
				"ElementarySchool" => "&mdash; Elementary School",
				"HighSchool" => "&mdash; High School",
				"MiddleSchool" => "&mdash; Middle School",
				"Preschool" => "&mdash; Preschool",
				"School" => "&mdash; School",

			),
			"PerformingGroup" => array(
				"PerformingGroup" => "Performing Group",
				"DanceGroup" => "&mdash; Dance Group",
				"MusicGroup" => "&mdash; Music Group",
				"TheaterGroup" => "&mdash; Theater Group",

			),
			"SportsTeam" => "Sports Team",
			"LocalBusiness" => "Local Business",
			"AnimalShelter" => "Animal Shelter",
			"AutomotiveBusiness" => array(
				"AutomotiveBusiness" => "Automotive Business",
				"AutoBodyShop" => "&mdash; Auto Body Shop",
				"AutoDealer" => "&mdash; Auto Dealer",
				"AutoPartsStore" => "&mdash; Auto Parts Store",
				"AutoRental" => "&mdash; Auto Rental",
				"AutoRepair" => "&mdash; Auto Repair",
				"AutoWash" => "&mdash; Auto Wash",
				"GasStation" => "&mdash; Gas Station",
				"MotorcycleDealer" => "&mdash; Motorcycle Dealer",
				"MotorcycleRepair" => "&mdash; Motorcycle Repair",
			),
			"ChildCare" => "Child Care",
			"DryCleaningOrLaundry" => "Dry Cleaning or Laundry",
			"EmergencyService" => array(
				"EmergencyService" => "Emergency Service",
				"FireStation" => "&mdash; Fire Station",
				"Hospital" => "&mdash; Hospital",
				"PoliceStation" => "&mdash; Police Station",
			),
			"EmploymentAgency" => "Employment Agency",
			"EntertainmentBusiness" => array(
				"EntertainmentBusiness" => "Entertainment Business",
				"AdultEntertainment" => "&mdash; Adult Entertainment",
				"AmusementPark" => "&mdash; Amusement Park",
				"ArtGallery" => "&mdash; Art Gallery",
				"Casino" => "&mdash; Casino",
				"ComedyClub" => "&mdash; Comedy Club",
				"MovieTheater" => "&mdash; Movie Theater",
				"NightClub" => "&mdash; Night Club",

			),
			"FinancialService" => array(
				"FinancialService" => "Financial Service",
				"AccountingService" => "&mdash; Accounting Service",
				"AutomatedTeller" => "&mdash; Automated Teller",
				"BankOrCreditUnion" => "&mdash; Bank or Credit Union",
				"InsuranceAgency" => "&mdash; Insurance Agency",
			),
			"FoodEstablishment" => array(
				"FoodEstablishment" => "Food Establishment",
				"Bakery" => "&mdash; Bakery",
				"BarOrPub" => "&mdash; Bar or Pub",
				"Brewery" => "&mdash; Brewery",
				"CafeOrCoffeeShop" => "&mdash; Cafe or Coffee Shop",
				"FastFoodRestaurant" => "&mdash; Fast Food Restaurant",
				"IceCreamShop" => "&mdash; Ice Cream Shop",
				"Restaurant" => "&mdash; Restaurant",
				"Winery" => "&mdash; Winery",
			),
			"GovernmentOffice" => array(
				"GovernmentOffice" => "Government Office",
				"PostOffice" => "&mdash; Post Office",
			),
			"HealthAndBeautyBusiness" => array(
				"HealthAndBeautyBusiness" => "Health And Beauty Business",
				"BeautySalon" => "&mdash; Beauty Salon",
				"DaySpa" => "&mdash; Day Spa",
				"HairSalon" => "&mdash; Hair Salon",
				"HealthClub" => "&mdash; Health Club",
				"NailSalon" => "&mdash; Nail Salon",
				"TattooParlor" => "&mdash; Tattoo Parlor",
			),
			"HomeAndConstructionBusiness" => array(
				"HomeAndConstructionBusiness" => "Home And Construction Business",
				"Electrician" => "&mdash; Electrician",
				"GeneralContractor" => "&mdash; General Contractor",
				"HVACBusiness" => "&mdash; HVAC Business",
				"HousePainter" => "&mdash; House Painter",
				"Locksmith" => "&mdash; Locksmith",
				"MovingCompany" => "&mdash; Moving Company",
				"Plumber" => "&mdash; Plumber",
				"RoofingContractor" => "&mdash; Roofing Contractor",
			),
			"InternetCafe" => "Internet Cafe",
			"Library" => " Library",
			"LodgingBusiness" => array(
				"LodgingBusiness" => "Lodging Business",
				"BedAndBreakfast" => "&mdash; Bed And Breakfast",
				"Hostel" => "&mdash; Hostel",
				"Hotel" => "&mdash; Hotel",
				"Motel" => "&mdash; Motel",
			),
			"MedicalOrganization" => array(
				"MedicalOrganization" => "Medical Organization",
				"Dentist" => "&mdash; Dentist",
				"DiagnosticLab" => "&mdash; Diagnostic Lab",
				"Hospital" => "&mdash; Hospital",
				"MedicalClinic" => "&mdash; Medical Clinic",
				"Optician" => "&mdash; Optician",
				"Pharmacy" => "&mdash; Pharmacy",
				"Physician" => "&mdash; Physician",
				"VeterinaryCare" => "&mdash; Veterinary Care",
			),
			"ProfessionalService" => array(
				"ProfessionalService" => "Professional Service",
				"AccountingService" => "&mdash; Accounting Service",
				"Attorney" => "&mdash; Attorney",
				"Dentist" => "&mdash; Dentist",
				"Electrician" => "&mdash; Electrician",
				"GeneralContractor" => "&mdash; General Contractor",
				"HousePainter" => "&mdash; House Painter",
				"Locksmith" => "&mdash; Locksmith",
				"Notary" => "&mdash; Notary",
				"Plumber" => "&mdash; Plumber",
				"RoofingContractor" => "&mdash; Roofing Contractor",
			),
			"RadioStation" => "Radio Station",
			"RealEstateAgent" => "Real Estate Agent",
			"RecyclingCenter" => "Recycling Center",
			"SelfStorage" => "Self Storage",
			"ShoppingCenter" => "Shopping Center",
			"SportsActivityLocation" => array(
				"SportsActivityLocation" => "Sports Activity Location",
				"BowlingAlley" => "&mdash; Bowling Alley",
				"ExerciseGym" => "&mdash; Exercise Gym",
				"GolfCourse" => "&mdash; Golf Course",
				"HealthClub" => "&mdash; Health Club",
				"PublicSwimmingPool" => "&mdash; Public Swimming Pool",
				"SkiResort" => "&mdash; Ski Resort",
				"SportsClub" => "&mdash; Sports Club",
				"StadiumOrArena" => "&mdash; Stadium or Arena",
				"TennisComplex" => "&mdash; Tennis Complex",
			),
			"Store" => array(
				"Store" => " Store",
				"AutoPartsStore" => "&mdash; Auto Parts Store",
				"BikeStore" => "&mdash; Bike Store",
				"BookStore" => "&mdash; Book Store",
				"ClothingStore" => "&mdash; Clothing Store",
				"ComputerStore" => "&mdash; Computer Store",
				"ConvenienceStore" => "&mdash; Convenience Store",
				"DepartmentStore" => "&mdash; Department Store",
				"ElectronicsStore" => "&mdash; Electronics Store",
				"Florist" => "&mdash; Florist",
				"FurnitureStore" => "&mdash; Furniture Store",
				"GardenStore" => "&mdash; Garden Store",
				"GroceryStore" => "&mdash; Grocery Store",
				"HardwareStore" => "&mdash; Hardware Store",
				"HobbyShop" => "&mdash; Hobby Shop",
				"HomeGoodsStore" => "&mdash; HomeGoods Store",
				"JewelryStore" => "&mdash; Jewelry Store",
				"LiquorStore" => "&mdash; Liquor Store",
				"MensClothingStore" => "&mdash; Mens Clothing Store",
				"MobilePhoneStore" => "&mdash; Mobile Phone Store",
				"MovieRentalStore" => "&mdash; Movie Rental Store",
				"MusicStore" => "&mdash; Music Store",
				"OfficeEquipmentStore" => "&mdash; Office Equipment Store",
				"OutletStore" => "&mdash; Outlet Store",
				"PawnShop" => "&mdash; Pawn Shop",
				"PetStore" => "&mdash; Pet Store",
				"ShoeStore" => "&mdash; Shoe Store",
				"SportingGoodsStore" => "&mdash; Sporting Goods Store",
				"TireShop" => "&mdash; Tire Shop",
				"ToyStore" => "&mdash; Toy Store",
				"WholesaleStore" => "&mdash; Wholesale Store",
			),
			"TelevisionStation" => "Television Station",
			"TouristInformationCenter" => "Tourist Information Center",
			"TravelAgency" => "Travel Agency",
			"Airport" => "Airport",
			"Aquarium" => "Aquarium",
			"Beach" => "Beach",
			"BusStation" => "BusStation",
			"BusStop" => "BusStop",
			"Campground" => "Campground",
			"Cemetery" => "Cemetery",
			"Crematorium" => "Crematorium",
			"EventVenue" => "Event Venue",
			"FireStation" => "Fire Station",
			"GovernmentBuilding" => array(
				"GovernmentBuilding" => "Government Building",
				"CityHall" => "&mdash; City Hall",
				"Courthouse" => "&mdash; Courthouse",
				"DefenceEstablishment" => "&mdash; Defence Establishment",
				"Embassy" => "&mdash; Embassy",
				"LegislativeBuilding" => "&mdash; Legislative Building",
			),
			"Hospital" => "Hospital",
			"MovieTheater" => "Movie Theater",
			"Museum" => "Museum",
			"MusicVenue" => "Music Venue",
			"Park" => "Park",
			"ParkingFacility" => "Parking Facility",
			"PerformingArtsTheater" => "Performing Arts Theater",
			"PlaceOfWorship" => array(
				"PlaceOfWorship" => "Place Of Worship",
				"BuddhistTemple" => "&mdash; Buddhist Temple",
				"CatholicChurch" => "&mdash; Catholic Church",
				"Church" => "&mdash; Church",
				"HinduTemple" => "&mdash; Hindu Temple",
				"Mosque" => "&mdash; Mosque",
				"Synagogue" => "&mdash; Synagogue",
			),
			"Playground" => "Playground",
			"PoliceStation" => "PoliceStation",
			"RVPark" => "RVPark",
			"StadiumOrArena" => "StadiumOrArena",
			"SubwayStation" => "SubwayStation",
			"TaxiStand" => "TaxiStand",
			"TrainStation" => "TrainStation",
			"Zoo" => "Zoo",
			"Residence" => array(
				"Residence" => "Residence",
				"ApartmentComplex" => "&mdash; Apartment Complex",
				"GatedResidenceCommunity" => "&mdash; Gated Residence Community",
				"SingleFamilyResidence" => "&mdash; Single Family Residence",
			),
		);
	}
}
