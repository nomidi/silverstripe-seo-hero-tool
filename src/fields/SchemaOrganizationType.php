<?php

namespace nomidi\SeoHeroTool;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\GroupedDropdownField;

/**
 *
 *    SchemaOrganizationTypeField
 *
 *
 * @package    SeoHeroTool
 * @copyright  Copyright (c) 2017 Bastian Fritsch (http://www.bit-basti.de)
 * @license    BSD 2-clause license
 */
class SchemaOrganizationType extends GroupedDropdownField {

	protected $extraClasses = array('dropdown');

	public function __construct($name, $title = null, $source = null, $value = "", $form = null) {
		if (!is_array($source)) {
			$source = self::getSchemOrganizationTypes();
		}
		parent::__construct($name, ($title === null) ? $name : $title, $source, $value, $form);
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
				"CollegeOrUniversity" => "– College or University",
				"ElementarySchool" => "– Elementary School",
				"HighSchool" => "– High School",
				"MiddleSchool" => "– Middle School",
				"Preschool" => "– Preschool",
				"School" => "– School",

			),
			"PerformingGroup" => array(
				"PerformingGroup" => "Performing Group",
				"DanceGroup" => "– Dance Group",
				"MusicGroup" => "– Music Group",
				"TheaterGroup" => "– Theater Group",

			),
			"SportsTeam" => "Sports Team",
			"LocalBusiness" => "Local Business",
			"AnimalShelter" => "Animal Shelter",
			"AutomotiveBusiness" => array(
				"AutomotiveBusiness" => "Automotive Business",
				"AutoBodyShop" => "– Auto Body Shop",
				"AutoDealer" => "– Auto Dealer",
				"AutoPartsStore" => "– Auto Parts Store",
				"AutoRental" => "– Auto Rental",
				"AutoRepair" => "– Auto Repair",
				"AutoWash" => "– Auto Wash",
				"GasStation" => "– Gas Station",
				"MotorcycleDealer" => "– Motorcycle Dealer",
				"MotorcycleRepair" => "– Motorcycle Repair",
			),
			"ChildCare" => "Child Care",
			"DryCleaningOrLaundry" => "Dry Cleaning or Laundry",
			"EmergencyService" => array(
				"EmergencyService" => "Emergency Service",
				"FireStation" => "– Fire Station",
				"Hospital" => "– Hospital",
				"PoliceStation" => "– Police Station",
			),
			"EmploymentAgency" => "Employment Agency",
			"EntertainmentBusiness" => array(
				"EntertainmentBusiness" => "Entertainment Business",
				"AdultEntertainment" => "– Adult Entertainment",
				"AmusementPark" => "– Amusement Park",
				"ArtGallery" => "– Art Gallery",
				"Casino" => "– Casino",
				"ComedyClub" => "– Comedy Club",
				"MovieTheater" => "– Movie Theater",
				"NightClub" => "– Night Club",

			),
			"FinancialService" => array(
				"FinancialService" => "Financial Service",
				"AccountingService" => "– Accounting Service",
				"AutomatedTeller" => "– Automated Teller",
				"BankOrCreditUnion" => "– Bank or Credit Union",
				"InsuranceAgency" => "– Insurance Agency",
			),
			"FoodEstablishment" => array(
				"FoodEstablishment" => "Food Establishment",
				"Bakery" => "– Bakery",
				"BarOrPub" => "– Bar or Pub",
				"Brewery" => "– Brewery",
				"CafeOrCoffeeShop" => "– Cafe or Coffee Shop",
				"FastFoodRestaurant" => "– Fast Food Restaurant",
				"IceCreamShop" => "– Ice Cream Shop",
				"Restaurant" => "– Restaurant",
				"Winery" => "– Winery",
			),
			"GovernmentOffice" => array(
				"GovernmentOffice" => "Government Office",
				"PostOffice" => "– Post Office",
			),
			"HealthAndBeautyBusiness" => array(
				"HealthAndBeautyBusiness" => "Health And Beauty Business",
				"BeautySalon" => "– Beauty Salon",
				"DaySpa" => "– Day Spa",
				"HairSalon" => "– Hair Salon",
				"HealthClub" => "– Health Club",
				"NailSalon" => "– Nail Salon",
				"TattooParlor" => "– Tattoo Parlor",
			),
			"HomeAndConstructionBusiness" => array(
				"HomeAndConstructionBusiness" => "Home And Construction Business",
				"Electrician" => "– Electrician",
				"GeneralContractor" => "– General Contractor",
				"HVACBusiness" => "– HVAC Business",
				"HousePainter" => "– House Painter",
				"Locksmith" => "– Locksmith",
				"MovingCompany" => "– Moving Company",
				"Plumber" => "– Plumber",
				"RoofingContractor" => "– Roofing Contractor",
			),
			"InternetCafe" => "Internet Cafe",
			"Library" => " Library",
			"LodgingBusiness" => array(
				"LodgingBusiness" => "Lodging Business",
				"BedAndBreakfast" => "– Bed And Breakfast",
				"Hostel" => "– Hostel",
				"Hotel" => "– Hotel",
				"Motel" => "– Motel",
			),
			"MedicalOrganization" => array(
				"MedicalOrganization" => "Medical Organization",
				"Dentist" => "– Dentist",
				"DiagnosticLab" => "– Diagnostic Lab",
				"Hospital" => "– Hospital",
				"MedicalClinic" => "– Medical Clinic",
				"Optician" => "– Optician",
				"Pharmacy" => "– Pharmacy",
				"Physician" => "– Physician",
				"VeterinaryCare" => "– Veterinary Care",
			),
			"ProfessionalService" => array(
				"ProfessionalService" => "Professional Service",
				"AccountingService" => "– Accounting Service",
				"Attorney" => "– Attorney",
				"Dentist" => "– Dentist",
				"Electrician" => "– Electrician",
				"GeneralContractor" => "– General Contractor",
				"HousePainter" => "– House Painter",
				"Locksmith" => "– Locksmith",
				"Notary" => "– Notary",
				"Plumber" => "– Plumber",
				"RoofingContractor" => "– Roofing Contractor",
			),
			"RadioStation" => "Radio Station",
			"RealEstateAgent" => "Real Estate Agent",
			"RecyclingCenter" => "Recycling Center",
			"SelfStorage" => "Self Storage",
			"ShoppingCenter" => "Shopping Center",
			"SportsActivityLocation" => array(
				"SportsActivityLocation" => "Sports Activity Location",
				"BowlingAlley" => "– Bowling Alley",
				"ExerciseGym" => "– Exercise Gym",
				"GolfCourse" => "– Golf Course",
				"HealthClub" => "– Health Club",
				"PublicSwimmingPool" => "– Public Swimming Pool",
				"SkiResort" => "– Ski Resort",
				"SportsClub" => "– Sports Club",
				"StadiumOrArena" => "– Stadium or Arena",
				"TennisComplex" => "– Tennis Complex",
			),
			"Store" => array(
				"Store" => " Store",
				"AutoPartsStore" => "– Auto Parts Store",
				"BikeStore" => "– Bike Store",
				"BookStore" => "– Book Store",
				"ClothingStore" => "– Clothing Store",
				"ComputerStore" => "– Computer Store",
				"ConvenienceStore" => "– Convenience Store",
				"DepartmentStore" => "– Department Store",
				"ElectronicsStore" => "– Electronics Store",
				"Florist" => "– Florist",
				"FurnitureStore" => "– Furniture Store",
				"GardenStore" => "– Garden Store",
				"GroceryStore" => "– Grocery Store",
				"HardwareStore" => "– Hardware Store",
				"HobbyShop" => "– Hobby Shop",
				"HomeGoodsStore" => "– HomeGoods Store",
				"JewelryStore" => "– Jewelry Store",
				"LiquorStore" => "– Liquor Store",
				"MensClothingStore" => "– Mens Clothing Store",
				"MobilePhoneStore" => "– Mobile Phone Store",
				"MovieRentalStore" => "– Movie Rental Store",
				"MusicStore" => "– Music Store",
				"OfficeEquipmentStore" => "– Office Equipment Store",
				"OutletStore" => "– Outlet Store",
				"PawnShop" => "– Pawn Shop",
				"PetStore" => "– Pet Store",
				"ShoeStore" => "– Shoe Store",
				"SportingGoodsStore" => "– Sporting Goods Store",
				"TireShop" => "– Tire Shop",
				"ToyStore" => "– Toy Store",
				"WholesaleStore" => "– Wholesale Store",
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
				"CityHall" => "– City Hall",
				"Courthouse" => "– Courthouse",
				"DefenceEstablishment" => "– Defence Establishment",
				"Embassy" => "– Embassy",
				"LegislativeBuilding" => "– Legislative Building",
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
				"BuddhistTemple" => "– Buddhist Temple",
				"CatholicChurch" => "– Catholic Church",
				"Church" => "– Church",
				"HinduTemple" => "– Hindu Temple",
				"Mosque" => "– Mosque",
				"Synagogue" => "– Synagogue",
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
				"ApartmentComplex" => "– Apartment Complex",
				"GatedResidenceCommunity" => "– Gated Residence Community",
				"SingleFamilyResidence" => "– Single Family Residence",
			),
		);
	}
}
