var input = "Emoji search...",
msEmo = [
{
	"name": "People",
	"emojies": [
	{
		"emj": "😀",
		"title": "Grinning Face"
	},
	{
		"emj": "😁",
		"title": "Beaming Face With Smiling Eyes"
	},
	{
		"emj": "😂",
		"title": "Face With Tears Of Joy"
	},
	{
		"emj": "🤣",
		"title": "Rolling On The Floor Laughing"
	},
	{
		"emj": "😃",
		"title": "Grinning Face With Big Eyes"
	},
	{
		"emj": "😄",
		"title": "Grinning Face With Smiling Eyes"
	},
	{
		"emj": "😅",
		"title": "Grinning Face With Sweat"
	},
	{
		"emj": "😆",
		"title": "Grinning Squinting Face"
	},
	{
		"emj": "😉",
		"title": "Winking Face"
	},
	{
		"emj": "😊",
		"title": "Smiling Face With Smiling Eyes"
	},
	{
		"emj": "😋",
		"title": "Face Savoring Food"
	},
	{
		"emj": "😎",
		"title": "Smiling Face With Sunglasses"
	},
	{
		"emj": "😍",
		"title": "Smiling Face With Heart-eyes"
	},
	{
		"emj": "😘",
		"title": "Face Blowing A Kiss"
	},
	{
		"emj": "😗",
		"title": "Kissing Face"
	},
	{
		"emj": "😙",
		"title": "Kissing Face With Smiling Eyes"
	},
	{
		"emj": "😚",
		"title": "Kissing Face With Closed Eyes"
	},
	{
		"emj": "🙂",
		"title": "Slightly Smiling Face"
	},
	{
		"emj": "🤗",
		"title": "Hugging Face"
	},
	{
		"emj": "🤩",
		"title": "Star-struck"
	},
	{
		"emj": "🤔",
		"title": "Thinking Face"
	},
	{
		"emj": "🤨",
		"title": "Face With Raised Eyebrow"
	},
	{
		"emj": "😐",
		"title": "Neutral Face"
	},
	{
		"emj": "😑",
		"title": "Expressionless Face"
	},
	{
		"emj": "😶",
		"title": "Face Without Mouth"
	},
	{
		"emj": "🙄",
		"title": "Face With Rolling Eyes"
	},
	{
		"emj": "😏",
		"title": "Smirking Face"
	},
	{
		"emj": "😣",
		"title": "Persevering Face"
	},
	{
		"emj": "😥",
		"title": "Sad But Relieved Face"
	},
	{
		"emj": "😮",
		"title": "Face With Open Mouth"
	},
	{
		"emj": "🤐",
		"title": "Zipper-mouth Face"
	},
	{
		"emj": "😯",
		"title": "Hushed Face"
	},
	{
		"emj": "😪",
		"title": "Sleepy Face"
	},
	{
		"emj": "😫",
		"title": "Tired Face"
	},
	{
		"emj": "😴",
		"title": "Sleeping Face"
	},
	{
		"emj": "😌",
		"title": "Relieved Face"
	},
	{
		"emj": "😛",
		"title": "Face With Tongue"
	},
	{
		"emj": "😜",
		"title": "Winking Face With Tongue"
	},
	{
		"emj": "😝",
		"title": "Squinting Face With Tongue"
	},
	{
		"emj": "🤤",
		"title": "Drooling Face"
	},
	{
		"emj": "😒",
		"title": "Unamused Face"
	},
	{
		"emj": "😓",
		"title": "Downcast Face With Sweat"
	},
	{
		"emj": "😔",
		"title": "Pensive Face"
	},
	{
		"emj": "😕",
		"title": "Confused Face"
	},
	{
		"emj": "🙃",
		"title": "Upside-down Face"
	},
	{
		"emj": "🤑",
		"title": "Money-mouth Face"
	},
	{
		"emj": "😲",
		"title": "Astonished Face"
	},
	{
		"emj": "🙁",
		"title": "Slightly Frowning Face"
	},
	{
		"emj": "😖",
		"title": "Confounded Face"
	},
	{
		"emj": "😞",
		"title": "Disappointed Face"
	},
	{
		"emj": "😟",
		"title": "Worried Face"
	},
	{
		"emj": "😤",
		"title": "Face With Steam From Nose"
	},
	{
		"emj": "😢",
		"title": "Crying Face"
	},
	{
		"emj": "😭",
		"title": "Loudly Crying Face"
	},
	{
		"emj": "😦",
		"title": "Frowning Face With Open Mouth"
	},
	{
		"emj": "😧",
		"title": "Anguished Face"
	},
	{
		"emj": "😨",
		"title": "Fearful Face"
	},
	{
		"emj": "😩",
		"title": "Weary Face"
	},
	{
		"emj": "🤯",
		"title": "Exploding Head"
	},
	{
		"emj": "😬",
		"title": "Grimacing Face"
	},
	{
		"emj": "😰",
		"title": "Anxious Face With Sweat"
	},
	{
		"emj": "😱",
		"title": "Face Screaming In Fear"
	},
	{
		"emj": "😳",
		"title": "Flushed Face"
	},
	{
		"emj": "🤪",
		"title": "Zany Face"
	},
	{
		"emj": "😵",
		"title": "Dizzy Face"
	},
	{
		"emj": "😡",
		"title": "Pouting Face"
	},
	{
		"emj": "😠",
		"title": "Angry Face"
	},
	{
		"emj": "🤬",
		"title": "Face With Symbols On Mouth"
	},
	{
		"emj": "😷",
		"title": "Face With Medical Mask"
	},
	{
		"emj": "🤒",
		"title": "Face With Thermometer"
	},
	{
		"emj": "🤕",
		"title": "Face With Head-bandage"
	},
	{
		"emj": "🤢",
		"title": "Nauseated Face"
	},
	{
		"emj": "🤮",
		"title": "Face Vomiting"
	},
	{
		"emj": "🤧",
		"title": "Sneezing Face"
	},
	{
		"emj": "😇",
		"title": "Smiling Face With Halo"
	},
	{
		"emj": "🤠",
		"title": "Cowboy Hat Face"
	},
	{
		"emj": "🤥",
		"title": "Lying Face"
	},
	{
		"emj": "🤫",
		"title": "Shushing Face"
	},
	{
		"emj": "🤭",
		"title": "Face With Hand Over Mouth"
	},
	{
		"emj": "🧐",
		"title": "Face With Monocle"
	},
	{
		"emj": "🤓",
		"title": "Nerd Face"
	},
	{
		"emj": "😈",
		"title": "Smiling Face With Horns"
	},
	{
		"emj": "👿",
		"title": "Angry Face With Horns"
	},
	{
		"emj": "🤡",
		"title": "Clown Face"
	},
	{
		"emj": "👹",
		"title": "Ogre"
	},
	{
		"emj": "👺",
		"title": "Goblin"
	},
	{
		"emj": "💀",
		"title": "Skull"
	},
	{
		"emj": "☠",
		"title": "Skull And Crossbones"
	},
	{
		"emj": "👻",
		"title": "Ghost"
	},
	{
		"emj": "👽",
		"title": "Alien"
	},
	{
		"emj": "👾",
		"title": "Alien Monster"
	},
	{
		"emj": "🤖",
		"title": "Robot Face"
	},
	{
		"emj": "💩",
		"title": "Pile Of Poo"
	},
	{
		"emj": "😺",
		"title": "Grinning Cat Face"
	},
	{
		"emj": "😸",
		"title": "Grinning Cat Face With Smiling Eyes"
	},
	{
		"emj": "😹",
		"title": "Cat Face With Tears Of Joy"
	},
	{
		"emj": "😻",
		"title": "Smiling Cat Face With Heart-eyes"
	},
	{
		"emj": "😼",
		"title": "Cat Face With Wry Smile"
	},
	{
		"emj": "😽",
		"title": "Kissing Cat Face"
	},
	{
		"emj": "🙀",
		"title": "Weary Cat Face"
	},
	{
		"emj": "😿",
		"title": "Crying Cat Face"
	},
	{
		"emj": "😾",
		"title": "Pouting Cat Face"
	},
	{
		"emj": "🙈",
		"title": "See-no-evil Monkey"
	},
	{
		"emj": "🙉",
		"title": "Hear-no-evil Monkey"
	},
	{
		"emj": "🙊",
		"title": "Speak-no-evil Monkey"
	},
	{
		"emj": "👶",
		"title": "Baby"
	},
	{
		"emj": "🧒",
		"title": "Child"
	},
	{
		"emj": "👦",
		"title": "Boy"
	},
	{
		"emj": "👧",
		"title": "Girl"
	},
	{
		"emj": "🧑",
		"title": "Adult"
	},
	{
		"emj": "👨",
		"title": "Man"
	},
	{
		"emj": "👩",
		"title": "Woman"
	},
	{
		"emj": "🧓",
		"title": "Older Adult"
	},
	{
		"emj": "👴",
		"title": "Old Man"
	},
	{
		"emj": "👵",
		"title": "Old Woman"
	},
	{
		"emj": "👨‍⚕️",
		"title": "Man Health Worker"
	},
	{
		"emj": "👩‍⚕️",
		"title": "Woman Health Worker"
	},
	{
		"emj": "👨‍🎓",
		"title": "Man Student"
	},
	{
		"emj": "👩‍🎓",
		"title": "Woman Student"
	},
	{
		"emj": "👨‍🏫",
		"title": "Man Teacher"
	},
	{
		"emj": "👩‍🏫",
		"title": "Woman Teacher"
	},
	{
		"emj": "👨‍⚖️",
		"title": "Man Judge"
	},
	{
		"emj": "👩‍⚖️",
		"title": "Woman Judge"
	},
	{
		"emj": "👨‍🌾",
		"title": "Man Farmer"
	},
	{
		"emj": "👩‍🌾",
		"title": "Woman Farmer"
	},
	{
		"emj": "👨‍🍳",
		"title": "Man Cook"
	},
	{
		"emj": "👩‍🍳",
		"title": "Woman Cook"
	},
	{
		"emj": "👨‍🔧",
		"title": "Man Mechanic"
	},
	{
		"emj": "👩‍🔧",
		"title": "Woman Mechanic"
	},
	{
		"emj": "👨‍🏭",
		"title": "Man Factory Worker"
	},
	{
		"emj": "👩‍🏭",
		"title": "Woman Factory Worker"
	},
	{
		"emj": "👨‍💼",
		"title": "Man Office Worker"
	},
	{
		"emj": "👩‍💼",
		"title": "Woman Office Worker"
	},
	{
		"emj": "👨‍🔬",
		"title": "Man Scientist"
	},
	{
		"emj": "👩‍🔬",
		"title": "Woman Scientist"
	},
	{
		"emj": "👨‍💻",
		"title": "Man Technologist"
	},
	{
		"emj": "👩‍💻",
		"title": "Woman Technologist"
	},
	{
		"emj": "👨‍🎤",
		"title": "Man Singer"
	},
	{
		"emj": "👩‍🎤",
		"title": "Woman Singer"
	},
	{
		"emj": "👨‍🎨",
		"title": "Man Artist"
	},
	{
		"emj": "👩‍🎨",
		"title": "Woman Artist"
	},
	{
		"emj": "👨‍✈️",
		"title": "Man Pilot"
	},
	{
		"emj": "👩‍✈️",
		"title": "Woman Pilot"
	},
	{
		"emj": "👨‍🚀",
		"title": "Man Astronaut"
	},
	{
		"emj": "👩‍🚀",
		"title": "Woman Astronaut"
	},
	{
		"emj": "👨‍🚒",
		"title": "Man Firefighter"
	},
	{
		"emj": "👩‍🚒",
		"title": "Woman Firefighter"
	},
	{
		"emj": "👮‍♂️",
		"title": "Man Police Officer"
	},
	{
		"emj": "👮‍♀️‍",
		"title": "Woman Police Officer"
	},
	{
		"emj": "🕵️‍♂️",
		"title": "Man Detective"
	},
	{
		"emj": "🕵️‍♀️",
		"title": "Woman Detective"
	},
	{
		"emj": "💂‍♂️",
		"title": "Man Guard"
	},
	{
		"emj": "💂‍♀️",
		"title": "Woman Guard"
	},
	{
		"emj": "👷‍♂️",
		"title": "Man Construction Worker"
	},
	{
		"emj": "👷‍♀️",
		"title": "Woman Construction Worker"
	},
	{
		"emj": "🤴",
		"title": "Prince"
	},
	{
		"emj": "👸",
		"title": "Princess"
	},
	{
		"emj": "👳‍♂️",
		"title": "Man Wearing Turban"
	},
	{
		"emj": "👳‍♀️",
		"title": "Woman Wearing Turban"
	},
	{
		"emj": "👲",
		"title": "Man With Chinese Cap"
	},
	{
		"emj": "🧕",
		"title": "Woman With Headscarf"
	},
	{
		"emj": "🧔",
		"title": "Bearded Person"
	},
	{
		"emj": "👱‍♂️",
		"title": "Blond-haired Man"
	},
	{
		"emj": "👱‍♀️",
		"title": "Blond-haired Woman"
	},
	{
		"emj": "🤵",
		"title": "Man In Tuxedo"
	},
	{
		"emj": "👰",
		"title": "Bride With Veil"
	},
	{
		"emj": "🤰",
		"title": "Pregnant Woman"
	},
	{
		"emj": "🤱",
		"title": "Breast-feeding"
	},
	{
		"emj": "👼",
		"title": "Baby Angel"
	},
	{
		"emj": "🎅",
		"title": "Santa Claus"
	},
	{
		"emj": "🤶",
		"title": "Mrs. Claus"
	},
	{
		"emj": "🧙‍♀️",
		"title": "Woman Mage"
	},
	{
		"emj": "🧙‍♂️",
		"title": "Man Mage"
	},
	{
		"emj": "🧚‍♀️",
		"title": "Woman Fairy"
	},
	{
		"emj": "🧚‍♂️",
		"title": "Man Fairy"
	},
	{
		"emj": "🧛‍♀️",
		"title": "Woman Vampire"
	},
	{
		"emj": "🧛‍♂️",
		"title": "Man Vampire"
	},
	{
		"emj": "🧜‍♀️",
		"title": "Mermaid"
	},
	{
		"emj": "🧜‍♂️",
		"title": "Merman"
	},
	{
		"emj": "🧝‍♀️",
		"title": "Woman Elf"
	},
	{
		"emj": "🧝‍♂️",
		"title": "Man Elf"
	},
	{
		"emj": "🧞‍♀️",
		"title": "Woman Genie"
	},
	{
		"emj": "🧞‍♂️",
		"title": "Man Genie"
	},
	{
		"emj": "🧟‍♀️",
		"title": "Woman Zombie"
	},
	{
		"emj": "🧟‍♂️",
		"title": "Man Zombie"
	},
	{
		"emj": "🙍‍♂️",
		"title": "Man Frowning"
	},
	{
		"emj": "🙍‍♀️",
		"title": "Woman Frowning"
	},
	{
		"emj": "🙎‍♂️",
		"title": "Man Pouting"
	},
	{
		"emj": "🙎‍♀️",
		"title": "Woman Pouting"
	},
	{
		"emj": "🙅‍♂️",
		"title": "Man Gesturing NO"
	},
	{
		"emj": "🙅‍♀️",
		"title": "Woman Gesturing NO"
	},
	{
		"emj": "🙆‍♂️",
		"title": "Man Gesturing OK"
	},
	{
		"emj": "🙆‍♀️",
		"title": "Woman Gesturing OK"
	},
	{
		"emj": "💁‍♂️",
		"title": "Man Tipping Hand"
	},
	{
		"emj": "💁‍♀️",
		"title": "Woman Tipping Hand"
	},
	{
		"emj": "🙋‍♂️",
		"title": "Man Raising Hand"
	},
	{
		"emj": "🙋‍♀️",
		"title": "Woman Raising Hand"
	},
	{
		"emj": "🙇‍♂️",
		"title": "Man Bowing"
	},
	{
		"emj": "🙇‍♀️",
		"title": "Woman Bowing"
	},
	{
		"emj": "🤦‍♂️",
		"title": "Man Facepalming"
	},
	{
		"emj": "🤦‍♀️",
		"title": "Woman Facepalming"
	},
	{
		"emj": "🤷‍♂️",
		"title": "Man Shrugging"
	},
	{
		"emj": "🤷‍♀️",
		"title": "Woman Shrugging"
	},
	{
		"emj": "💆‍♂️",
		"title": "Man Getting Massage"
	},
	{
		"emj": "💆‍♀️",
		"title": "Woman Getting Massage"
	},
	{
		"emj": "💇‍♂️",
		"title": "Man Getting Haircut"
	},
	{
		"emj": "💇‍♀️",
		"title": "Woman Getting Haircut"
	},
	{
		"emj": "🚶‍♂️",
		"title": "Man Walking"
	},
	{
		"emj": "🚶‍♀️",
		"title": "Woman Walking"
	},
	{
		"emj": "🏃‍♂️",
		"title": "Man Running"
	},
	{
		"emj": "🏃‍♀️",
		"title": "Woman Running"
	},
	{
		"emj": "💃",
		"title": "Woman Dancing"
	},
	{
		"emj": "🕺",
		"title": "Man Dancing"
	},
	{
		"emj": "👯",
		"title": "People With Bunny Ears"
	},
	{
		"emj": "👯‍♂️",
		"title": "Men With Bunny Ears"
	},
	{
		"emj": "👯‍♀️",
		"title": "Women With Bunny Ears"
	},
	{
		"emj": "🧖‍♀️",
		"title": "Woman In Steamy Room"
	},
	{
		"emj": "🧖‍♂️",
		"title": "Man In Steamy Room"
	},
	{
		"emj": "🧗‍♀️",
		"title": "Woman Climbing"
	},
	{
		"emj": "🧗‍♂️",
		"title": "Man Climbing"
	},
	{
		"emj": "🧘‍♀️",
		"title": "Woman In Lotus Position"
	},
	{
		"emj": "🧘‍♂️",
		"title": "Man In Lotus Position"
	},
	{
		"emj": "🛀",
		"title": "Person Taking Bath"
	},
	{
		"emj": "🛌",
		"title": "Person In Bed"
	},
	{
		"emj": "🕴",
		"title": "Man In Suit Levitating"
	},
	{
		"emj": "🗣",
		"title": "Speaking Head"
	},
	{
		"emj": "👤",
		"title": "Bust In Silhouette"
	},
	{
		"emj": "👥",
		"title": "Busts In Silhouette"
	},
	{
		"emj": "🤺",
		"title": "Person Fencing"
	},
	{
		"emj": "🏇",
		"title": "Horse Racing"
	},
	{
		"emj": "⛷",
		"title": "Skier"
	},
	{
		"emj": "🏂",
		"title": "Snowboarder"
	},
	{
		"emj": "🏌️‍♂️",
		"title": "Man Golfing"
	},
	{
		"emj": "🏌️‍♀️",
		"title": "Woman Golfing"
	},
	{
		"emj": "🏄‍♂️",
		"title": "Man Surfing"
	},
	{
		"emj": "🏄‍♀️",
		"title": "Woman Surfing"
	},
	{
		"emj": "🚣‍♂️",
		"title": "Man Rowing Boat"
	},
	{
		"emj": "🚣‍♀️",
		"title": "Woman Rowing Boat"
	},
	{
		"emj": "🏊‍♂️",
		"title": "Man Swimming"
	},
	{
		"emj": "🏊‍♀️",
		"title": "Woman Swimming"
	},
	{
		"emj": "⛹️‍♂️",
		"title": "Man Bouncing Ball"
	},
	{
		"emj": "⛹️‍♀️",
		"title": "Woman Bouncing Ball"
	},
	{
		"emj": "🏋️‍♂️",
		"title": "Man Lifting Weights"
	},
	{
		"emj": "🏋️‍♀️",
		"title": "Woman Lifting Weights"
	},
	{
		"emj": "🚴‍♂️",
		"title": "Man Biking"
	},
	{
		"emj": "🚴‍♀️",
		"title": "Woman Biking"
	},
	{
		"emj": "🚵‍♂️",
		"title": "Man Mountain Biking"
	},
	{
		"emj": "🚵‍♀️",
		"title": "Woman Mountain Biking"
	},
	{
		"emj": "🏎",
		"title": "Racing Car"
	},
	{
		"emj": "🏍",
		"title": "Motorcycle"
	},
	{
		"emj": "🤸‍♂️",
		"title": "Man Cartwheeling"
	},
	{
		"emj": "🤸‍♀️",
		"title": "Woman Cartwheeling"
	},
	{
		"emj": "🤼",
		"title": "People Wrestling"
	},
	{
		"emj": "🤼‍♂️",
		"title": "Men Wrestling"
	},
	{
		"emj": "🤼‍♀️",
		"title": "Women Wrestling"
	},
	{
		"emj": "🤽‍♂️",
		"title": "Man Playing Water Polo"
	},
	{
		"emj": "🤽‍♀️",
		"title": "Woman Playing Water Polo"
	},
	{
		"emj": "🤾‍♂️",
		"title": "Man Playing Handball"
	},
	{
		"emj": "🤾‍♀️",
		"title": "Woman Playing Handball"
	},
	{
		"emj": "🤹‍♂️",
		"title": "Man Juggling"
	},
	{
		"emj": "🤹‍♀️",
		"title": "Woman Juggling"
	},
	{
		"emj": "👫",
		"title": "Man And Woman Holding Hands"
	},
	{
		"emj": "👬",
		"title": "Two Men Holding Hands"
	},
	{
		"emj": "👭",
		"title": "Two Women Holding Hands"
	},
	{
		"emj": "💏",
		"title": "Kiss"
	},
	{
		"emj": "👩‍❤️‍💋‍👨",
		"title": "Woman Kiss Man"
	},
	{
		"emj": "👨‍❤️‍💋‍👨",
		"title": "Man Kiss Man"
	},
	{
		"emj": "👩‍❤️‍💋‍👩",
		"title": "Woman Kiss Woman"
	},
	{
		"emj": "💑",
		"title": "Couple With Heart"
	},
	{
		"emj": "👩‍❤️‍👨",
		"title": "Couple With Heart: Woman Man"
	},
	{
		"emj": "👨‍❤️‍👨",
		"title": "Couple With Heart: Man Man"
	},
	{
		"emj": "👩‍❤️‍👩",
		"title": "Couple With Heart: Woman Woman"
	},
	{
		"emj": "👪",
		"title": "Family"
	},
	{
		"emj": "👨‍👩‍👦",
		"title": "Family: Man Woman Boy"
	},
	{
		"emj": "👨‍👩‍👧",
		"title": "Family: Man Woman Girl"
	},
	{
		"emj": "👨‍👩‍👧‍👦",
		"title": "Family: Man Woman Girl Boy"
	},
	{
		"emj": "👨‍👩‍👦‍👦",
		"title": "Family: Man Woman Boy Boy"
	},
	{
		"emj": "👨‍👩‍👧‍👧",
		"title": "Family: Man Woman Girl Girl"
	},
	{
		"emj": "👨‍👨‍👦",
		"title": "Family: Man Man Boy"
	},
	{
		"emj": "👨‍👨‍👧",
		"title": "Family: Man Man Girl"
	},
	{
		"emj": "👨‍👨‍👧‍👦",
		"title": "Family: Man Man Girl Boy"
	},
	{
		"emj": "👨‍👨‍👦‍👦",
		"title": "Family: Man Man Boy Boy"
	},
	{
		"emj": "👨‍👨‍👧‍👧",
		"title": "Family: Man Man Girl Girl"
	},
	{
		"emj": "👩‍👩‍👦",
		"title": "Family: Woman Woman Boy"
	},
	{
		"emj": "👩‍👩‍👧",
		"title": "Family: Woman Woman Girl"
	},
	{
		"emj": "👩‍👩‍👧‍👦",
		"title": "Family: Woman Woman Girl Boy"
	},
	{
		"emj": "👩‍👩‍👦‍👦",
		"title": "Family: Woman Woman Boy Boy"
	},
	{
		"emj": "👩‍👩‍👧‍👧",
		"title": "Family: Woman Woman Girl Girl"
	},
	{
		"emj": "👨‍👦",
		"title": "Family: Man Boy"
	},
	{
		"emj": "👨‍👦‍👦",
		"title": "Family: Man Boy Boy"
	},
	{
		"emj": "👨‍👧",
		"title": "Family: Man Girl"
	},
	{
		"emj": "👨‍👧‍👦",
		"title": "Family: Man Girl Boy"
	},
	{
		"emj": "👨‍👧‍👧",
		"title": "Family: Man Girl Girl"
	},
	{
		"emj": "👩‍👦",
		"title": "Family: Woman Boy"
	},
	{
		"emj": "👩‍👦‍👦",
		"title": "Family: Woman Boy Boy"
	},
	{
		"emj": "👩‍👧",
		"title": "Family: Woman Girl"
	},
	{
		"emj": "👩‍👧‍👦",
		"title": "Family: Woman Girl Boy"
	},
	{
		"emj": "👩‍👧‍👧",
		"title": "Family: Woman Girl Girl"
	},
	{
		"emj": "🤳",
		"title": "Selfie"
	},
	{
		"emj": "💪",
		"title": "Flexed Biceps"
	},
	{
		"emj": "👈",
		"title": "Backhand Index Pointing Left"
	},
	{
		"emj": "👉",
		"title": "Backhand Index Pointing Right"
	},
	{
		"emj": "☝",
		"title": "Index Pointing Up"
	},
	{
		"emj": "👆",
		"title": "Backhand Index Pointing Up"
	},
	{
		"emj": "🖕",
		"title": "Middle Finger"
	},
	{
		"emj": "👇",
		"title": "Backhand Index Pointing Down"
	},
	{
		"emj": "✌",
		"title": "Victory Hand"
	},
	{
		"emj": "🤞",
		"title": "Crossed Fingers"
	},
	{
		"emj": "🖖",
		"title": "Vulcan Salute"
	},
	{
		"emj": "🤘",
		"title": "Sign Of The Horns"
	},
	{
		"emj": "🤙",
		"title": "Call Me Hand"
	},
	{
		"emj": "🖐",
		"title": "Hand With Fingers Splayed"
	},
	{
		"emj": "✋",
		"title": "Raised Hand"
	},
	{
		"emj": "👌",
		"title": "OK Hand"
	},
	{
		"emj": "👍",
		"title": "Thumbs Up"
	},
	{
		"emj": "👎",
		"title": "Thumbs Down"
	},
	{
		"emj": "✊",
		"title": "Raised Fist"
	},
	{
		"emj": "👊",
		"title": "Oncoming Fist"
	},
	{
		"emj": "🤛",
		"title": "Left-facing Fist"
	},
	{
		"emj": "🤜",
		"title": "Right-facing Fist"
	},
	{
		"emj": "🤚",
		"title": "Raised Back Of Hand"
	},
	{
		"emj": "👋",
		"title": "Waving Hand"
	},
	{
		"emj": "🤟",
		"title": "Love-you Gesture"
	},
	{
		"emj": "✍",
		"title": "Writing Hand"
	},
	{
		"emj": "👏",
		"title": "Clapping Hands"
	},
	{
		"emj": "👐",
		"title": "Open Hands"
	},
	{
		"emj": "🙌",
		"title": "Raising Hands"
	},
	{
		"emj": "🤲",
		"title": "Palms Up Together"
	},
	{
		"emj": "🙏",
		"title": "Folded Hands"
	},
	{
		"emj": "🤝",
		"title": "Handshake"
	},
	{
		"emj": "💅",
		"title": "Nail Polish"
	},
	{
		"emj": "👂",
		"title": "Ear"
	},
	{
		"emj": "👃",
		"title": "Nose"
	},
	{
		"emj": "👣",
		"title": "Footprints"
	},
	{
		"emj": "👀",
		"title": "Eyes"
	},
	{
		"emj": "👁",
		"title": "Eye"
	},
	{
		"emj": "👁️‍🗨️",
		"title": "Eye In Speech Bubble"
	},
	{
		"emj": "🧠",
		"title": "Brain"
	},
	{
		"emj": "👅",
		"title": "Tongue"
	},
	{
		"emj": "👄",
		"title": "Mouth"
	},
	{
		"emj": "💋",
		"title": "Kiss Mark"
	},
	{
		"emj": "💘",
		"title": "Heart With Arrow"
	},
	{
		"emj": "❤",
		"title": "Red Heart"
	},
	{
		"emj": "💓",
		"title": "Beating Heart"
	},
	{
		"emj": "💔",
		"title": "Broken Heart"
	},
	{
		"emj": "💕",
		"title": "Two Hearts"
	},
	{
		"emj": "💖",
		"title": "Sparkling Heart"
	},
	{
		"emj": "💗",
		"title": "Growing Heart"
	},
	{
		"emj": "💙",
		"title": "Blue Heart"
	},
	{
		"emj": "💚",
		"title": "Green Heart"
	},
	{
		"emj": "💛",
		"title": "Yellow Heart"
	},
	{
		"emj": "🧡",
		"title": "Orange Heart"
	},
	{
		"emj": "💜",
		"title": "Purple Heart"
	},
	{
		"emj": "🖤",
		"title": "Black Heart"
	},
	{
		"emj": "💝",
		"title": "Heart With Ribbon"
	},
	{
		"emj": "💞",
		"title": "Revolving Hearts"
	},
	{
		"emj": "💟",
		"title": "Heart Decoration"
	},
	{
		"emj": "❣",
		"title": "Heavy Heart Exclamation"
	},
	{
		"emj": "💌",
		"title": "Love Letter"
	},
	{
		"emj": "💤",
		"title": "Zzz"
	},
	{
		"emj": "💢",
		"title": "Anger Symbol"
	},
	{
		"emj": "💣",
		"title": "Bomb"
	},
	{
		"emj": "💥",
		"title": "Collision"
	},
	{
		"emj": "💦",
		"title": "Sweat Droplets"
	},
	{
		"emj": "💨",
		"title": "Dashing Away"
	},
	{
		"emj": "💫",
		"title": "Dizzy"
	},
	{
		"emj": "💬",
		"title": "Speech Balloon"
	},
	{
		"emj": "🗨",
		"title": "Left Speech Bubble"
	},
	{
		"emj": "🗯",
		"title": "Right Anger Bubble"
	},
	{
		"emj": "💭",
		"title": "Thought Balloon"
	},
	{
		"emj": "🕳",
		"title": "Hole"
	},
	{
		"emj": "👓",
		"title": "Glasses"
	},
	{
		"emj": "🕶",
		"title": "Sunglasses"
	},
	{
		"emj": "👔",
		"title": "Necktie"
	},
	{
		"emj": "👕",
		"title": "T-shirt"
	},
	{
		"emj": "👖",
		"title": "Jeans"
	},
	{
		"emj": "🧣",
		"title": "Scarf"
	},
	{
		"emj": "🧤",
		"title": "Gloves"
	},
	{
		"emj": "🧥",
		"title": "Coat"
	},
	{
		"emj": "🧦",
		"title": "Socks"
	},
	{
		"emj": "👗",
		"title": "Dress"
	},
	{
		"emj": "👘",
		"title": "Kimono"
	},
	{
		"emj": "👙",
		"title": "Bikini"
	},
	{
		"emj": "👚",
		"title": "Woman’s Clothes"
	},
	{
		"emj": "👛",
		"title": "Purse"
	},
	{
		"emj": "👜",
		"title": "Handbag"
	},
	{
		"emj": "👝",
		"title": "Clutch Bag"
	},
	{
		"emj": "🛍",
		"title": "Shopping Bags"
	},
	{
		"emj": "🎒",
		"title": "Backpack"
	},
	{
		"emj": "👞",
		"title": "Man’s Shoe"
	},
	{
		"emj": "👟",
		"title": "Running Shoe"
	},
	{
		"emj": "👠",
		"title": "High-heeled Shoe"
	},
	{
		"emj": "👡",
		"title": "Woman’s Sandal"
	},
	{
		"emj": "👢",
		"title": "Woman’s Boot"
	},
	{
		"emj": "👑",
		"title": "Crown"
	},
	{
		"emj": "👒",
		"title": "Woman’s Hat"
	},
	{
		"emj": "🎩",
		"title": "Top Hat"
	},
	{
		"emj": "🎓",
		"title": "Graduation Cap"
	},
	{
		"emj": "🧢",
		"title": "Billed Cap"
	},
	{
		"emj": "⛑",
		"title": "Rescue Worker’s Helmet"
	},
	{
		"emj": "📿",
		"title": "Prayer Beads"
	},
	{
		"emj": "💄",
		"title": "Lipstick"
	},
	{
		"emj": "💍",
		"title": "Ring"
	},
	{
		"emj": "💎",
		"title": "Gem Stone"
	}
	]
},
{	
	"name": "Nature",
	"emojies": [
	{
		"emj": "🐵",
		"title": "Monkey Face"
	},
	{
		"emj": "🐒",
		"title": "Monkey"
	},
	{
		"emj": "🦍",
		"title": "Gorilla"
	},
	{
		"emj": "🐶",
		"title": "Dog Face"
	},
	{
		"emj": "🐕",
		"title": "Dog"
	},
	{
		"emj": "🐩",
		"title": "Poodle"
	},
	{
		"emj": "🐺",
		"title": "Wolf Face"
	},
	{
		"emj": "🦊",
		"title": "Fox Face"
	},
	{
		"emj": "🐱",
		"title": "Cat Face"
	},
	{
		"emj": "🐈",
		"title": "Cat"
	},
	{
		"emj": "🦁",
		"title": "Lion Face"
	},
	{
		"emj": "🐯",
		"title": "Tiger Face"
	},
	{
		"emj": "🐅",
		"title": "Tiger"
	},
	{
		"emj": "🐆",
		"title": "Leopard"
	},
	{
		"emj": "🐴",
		"title": "Horse Face"
	},
	{
		"emj": "🐎",
		"title": "Horse"
	},
	{
		"emj": "🦄",
		"title": "Unicorn Face"
	},
	{
		"emj": "🦓",
		"title": "Zebra"
	},
	{
		"emj": "🦌",
		"title": "Deer"
	},
	{
		"emj": "🐮",
		"title": "Cow Face"
	},
	{
		"emj": "🐂",
		"title": "Ox"
	},
	{
		"emj": "🐃",
		"title": "Water Buffalo"
	},
	{
		"emj": "🐄",
		"title": "Cow"
	},
	{
		"emj": "🐷",
		"title": "Pig Face"
	},
	{
		"emj": "🐖",
		"title": "Pig"
	},
	{
		"emj": "🐗",
		"title": "Boar"
	},
	{
		"emj": "🐽",
		"title": "Pig Nose"
	},
	{
		"emj": "🐏",
		"title": "Ram"
	},
	{
		"emj": "🐑",
		"title": "Ewe"
	},
	{
		"emj": "🐐",
		"title": "Goat"
	},
	{
		"emj": "🐪",
		"title": "Camel"
	},
	{
		"emj": "🐫",
		"title": "Two-hump Camel"
	},
	{
		"emj": "🦒",
		"title": "Giraffe"
	},
	{
		"emj": "🐘",
		"title": "Elephant"
	},
	{
		"emj": "🦏",
		"title": "Rhinoceros"
	},
	{
		"emj": "🐭",
		"title": "Mouse Face"
	},
	{
		"emj": "🐁",
		"title": "Mouse"
	},
	{
		"emj": "🐀",
		"title": "Rat"
	},
	{
		"emj": "🐹",
		"title": "Hamster Face"
	},
	{
		"emj": "🐰",
		"title": "Rabbit Face"
	},
	{
		"emj": "🐇",
		"title": "Rabbit"
	},
	{
		"emj": "🐿",
		"title": "Chipmunk"
	},
	{
		"emj": "🦔",
		"title": "Hedgehog"
	},
	{
		"emj": "🦇",
		"title": "Bat"
	},
	{
		"emj": "🐻",
		"title": "Bear Face"
	},
	{
		"emj": "🐨",
		"title": "Koala"
	},
	{
		"emj": "🐼",
		"title": "Panda Face"
	},
	{
		"emj": "🐾",
		"title": "Paw Prints"
	},
	{
		"emj": "🦃",
		"title": "Turkey"
	},
	{
		"emj": "🐔",
		"title": "Chicken"
	},
	{
		"emj": "🐓",
		"title": "Rooster"
	},
	{
		"emj": "🐣",
		"title": "Hatching Chick"
	},
	{
		"emj": "🐤",
		"title": "Baby Chick"
	},
	{
		"emj": "🐥",
		"title": "Front-facing Baby Chick"
	},
	{
		"emj": "🐦",
		"title": "Bird"
	},
	{
		"emj": "🐧",
		"title": "Penguin"
	},
	{
		"emj": "🕊",
		"title": "Dove"
	},
	{
		"emj": "🦅",
		"title": "Eagle"
	},
	{
		"emj": "🦆",
		"title": "Duck"
	},
	{
		"emj": "🦉",
		"title": "Owl"
	},
	{
		"emj": "🐸",
		"title": "Frog Face"
	},
	{
		"emj": "🐊",
		"title": "Crocodile"
	},
	{
		"emj": "🐢",
		"title": "Turtle"
	},
	{
		"emj": "🦎",
		"title": "Lizard"
	},
	{
		"emj": "🐍",
		"title": "Snake"
	},
	{
		"emj": "🐲",
		"title": "Dragon Face"
	},
	{
		"emj": "🐉",
		"title": "Dragon"
	},
	{
		"emj": "🦕",
		"title": "Sauropod"
	},
	{
		"emj": "🦖",
		"title": "T-Rex"
	},
	{
		"emj": "🐳",
		"title": "Spouting Whale"
	},
	{
		"emj": "🐋",
		"title": "Whale"
	},
	{
		"emj": "🐬",
		"title": "Dolphin"
	},
	{
		"emj": "🐟",
		"title": "Fish"
	},
	{
		"emj": "🐠",
		"title": "Tropical Fish"
	},
	{
		"emj": "🐡",
		"title": "Blowfish"
	},
	{
		"emj": "🦈",
		"title": "Shark"
	},
	{
		"emj": "🐙",
		"title": "Octopus"
	},
	{
		"emj": "🐚",
		"title": "Spiral Shell"
	},
	{
		"emj": "🦀",
		"title": "Crab"
	},
	{
		"emj": "🦐",
		"title": "Shrimp"
	},
	{
		"emj": "🦑",
		"title": "Squid"
	},
	{
		"emj": "🐌",
		"title": "Snail"
	},
	{
		"emj": "🦋",
		"title": "Butterfly"
	},
	{
		"emj": "🐛",
		"title": "Bug"
	},
	{
		"emj": "🐜",
		"title": "Ant"
	},
	{
		"emj": "🐝",
		"title": "Honeybee"
	},
	{
		"emj": "🐞",
		"title": "Lady Beetle"
	},
	{
		"emj": "🦗",
		"title": "Cricket"
	},
	{
		"emj": "🕷",
		"title": "Spider"
	},
	{
		"emj": "🕸",
		"title": "Spider Web"
	},
	{
		"emj": "🦂",
		"title": "Scorpion"
	},
	{
		"emj": "💐",
		"title": "Bouquet"
	},
	{
		"emj": "🌸",
		"title": "Cherry Blossom"
	},
	{
		"emj": "💮",
		"title": "White Flower"
	},
	{
		"emj": "🏵",
		"title": "Rosette"
	},
	{
		"emj": "🌹",
		"title": "Rose"
	},
	{
		"emj": "🥀",
		"title": "Wilted Flower"
	},
	{
		"emj": "🌺",
		"title": "Hibiscus"
	},
	{
		"emj": "🌻",
		"title": "Sunflower"
	},
	{
		"emj": "🌼",
		"title": "Blossom"
	},
	{
		"emj": "🌷",
		"title": "Tulip"
	},
	{
		"emj": "🌱",
		"title": "Seedling"
	},
	{
		"emj": "🌲",
		"title": "Evergreen Tree"
	},
	{
		"emj": "🌳",
		"title": "Deciduous Tree"
	},
	{
		"emj": "🌴",
		"title": "Palm Tree"
	},
	{
		"emj": "🌵",
		"title": "Cactus"
	},
	{
		"emj": "🌾",
		"title": "Sheaf Of Rice"
	},
	{
		"emj": "🌿",
		"title": "Herb"
	},
	{
		"emj": "☘",
		"title": "Shamrock"
	},
	{
		"emj": "🍀",
		"title": "Four Leaf Clover"
	},
	{
		"emj": "🍁",
		"title": "Maple Leaf"
	},
	{
		"emj": "🍂",
		"title": "Fallen Leaf"
	},
	{
		"emj": "🍃",
		"title": "Leaf Fluttering In Wind"
	}
	]
},
{
	"name": "Foof & Drink",
	"emojies": [
	{
		"emj": "🍇",
		"title": "Grapes"
	},
	{
		"emj": "🍈",
		"title": "Melon"
	},
	{
		"emj": "🍉",
		"title": "Watermelon"
	},
	{
		"emj": "🍊",
		"title": "Tangerine"
	},
	{
		"emj": "🍋",
		"title": "Lemon"
	},
	{
		"emj": "🍌",
		"title": "Banana"
	},
	{
		"emj": "🍍",
		"title": "Pineapple"
	},
	{
		"emj": "🍎",
		"title": "Red Apple"
	},
	{
		"emj": "🍏",
		"title": "Green Apple"
	},
	{
		"emj": "🍐",
		"title": "Pear"
	},
	{
		"emj": "🍑",
		"title": "Peach"
	},
	{
		"emj": "🍒",
		"title": "Cherries"
	},
	{
		"emj": "🍓",
		"title": "Strawberry"
	},
	{
		"emj": "🥝",
		"title": "Kiwi Fruit"
	},
	{
		"emj": "🍅",
		"title": "Tomato"
	},
	{
		"emj": "🥥",
		"title": "Coconut"
	},
	{
		"emj": "🥑",
		"title": "Avocado"
	},
	{
		"emj": "🍆",
		"title": "Eggplant"
	},
	{
		"emj": "🥔",
		"title": "Potato"
	},
	{
		"emj": "🥕",
		"title": "Carrot"
	},
	{
		"emj": "🌽",
		"title": "Ear Of Corn"
	},
	{
		"emj": "🌶",
		"title": "Hot Pepper"
	},
	{
		"emj": "🥒",
		"title": "Cucumber"
	},
	{
		"emj": "🥦",
		"title": "Broccoli"
	},
	{
		"emj": "🍄",
		"title": "Mushroom"
	},
	{
		"emj": "🥜",
		"title": "Peanuts"
	},
	{
		"emj": "🌰",
		"title": "Chestnut"
	},
	{
		"emj": "🍞",
		"title": "Bread"
	},
	{
		"emj": "🥐",
		"title": "Croissant"
	},
	{
		"emj": "🥖",
		"title": "Baguette Bread"
	},
	{
		"emj": "🥨",
		"title": "Pretzel"
	},
	{
		"emj": "🥞",
		"title": "Pancakes"
	},
	{
		"emj": "🧀",
		"title": "Cheese Wedge"
	},
	{
		"emj": "🍖",
		"title": "Meat On Bone"
	},
	{
		"emj": "🍗",
		"title": "Poultry Leg"
	},
	{
		"emj": "🥩",
		"title": "Cut Of Meat"
	},
	{
		"emj": "🥓",
		"title": "Bacon"
	},
	{
		"emj": "🍔",
		"title": "Hamburger"
	},
	{
		"emj": "🍟",
		"title": "French Fries"
	},
	{
		"emj": "🍕",
		"title": "Pizza"
	},
	{
		"emj": "🌭",
		"title": "Hot Dog"
	},
	{
		"emj": "🥪",
		"title": "Sandwich"
	},
	{
		"emj": "🌮",
		"title": "Taco"
	},
	{
		"emj": "🌯",
		"title": "Burrito"
	},
	{
		"emj": "🥙",
		"title": "Stuffed Flatbread"
	},
	{
		"emj": "🥚",
		"title": "Egg"
	},
	{
		"emj": "🍳",
		"title": "Cooking"
	},
	{
		"emj": "🥘",
		"title": "Shallow Pan Of Food"
	},
	{
		"emj": "🍲",
		"title": "Pot Of Food"
	},
	{
		"emj": "🥣",
		"title": "Bowl With Spoon"
	},
	{
		"emj": "🥗",
		"title": "Green Salad"
	},
	{
		"emj": "🍿",
		"title": "Popcorn"
	},
	{
		"emj": "🥫",
		"title": "Canned Food"
	},
	{
		"emj": "🍱",
		"title": "Bento Box"
	},
	{
		"emj": "🍘",
		"title": "Rice Cracker"
	},
	{
		"emj": "🍙",
		"title": "Rice Ball"
	},
	{
		"emj": "🍚",
		"title": "Cooked Rice"
	},
	{
		"emj": "🍛",
		"title": "Curry Rice"
	},
	{
		"emj": "🍜",
		"title": "Steaming Bowl"
	},
	{
		"emj": "🍝",
		"title": "Spaghetti"
	},
	{
		"emj": "🍠",
		"title": "Roasted Sweet Potato"
	},
	{
		"emj": "🍢",
		"title": "Oden"
	},
	{
		"emj": "🍣",
		"title": "Sushi"
	},
	{
		"emj": "🍤",
		"title": "Fried Shrimp"
	},
	{
		"emj": "🍥",
		"title": "Fish Cake With Swirl"
	},
	{
		"emj": "🍡",
		"title": "Dango"
	},
	{
		"emj": "🥟",
		"title": "Dumpling"
	},
	{
		"emj": "🥠",
		"title": "Fortune Cookie"
	},
	{
		"emj": "🥡",
		"title": "Takeout Box"
	},
	{
		"emj": "🍦",
		"title": "Soft Ice Cream"
	},
	{
		"emj": "🍧",
		"title": "Shaved Ice"
	},
	{
		"emj": "🍨",
		"title": "Ice Cream"
	},
	{
		"emj": "🍩",
		"title": "Doughnut"
	},
	{
		"emj": "🍪",
		"title": "Cookie"
	},
	{
		"emj": "🎂",
		"title": "Birthday Cake"
	},
	{
		"emj": "🍰",
		"title": "Shortcake"
	},
	{
		"emj": "🥧",
		"title": "Pie"
	},
	{
		"emj": "🍫",
		"title": "Chocolate Bar"
	},
	{
		"emj": "🍬",
		"title": "Candy"
	},
	{
		"emj": "🍭",
		"title": "Lollipop"
	},
	{
		"emj": "🍮",
		"title": "Custard"
	},
	{
		"emj": "🍯",
		"title": "Honey Pot"
	},
	{
		"emj": "🍼",
		"title": "Baby Bottle"
	},
	{
		"emj": "🥛",
		"title": "Glass Of Milk"
	},
	{
		"emj": "☕",
		"title": "Hot Beverage"
	},
	{
		"emj": "🍵",
		"title": "Teacup Without Handle"
	},
	{
		"emj": "🍶",
		"title": "Sake"
	},
	{
		"emj": "🍾",
		"title": "Bottle With Popping Cork"
	},
	{
		"emj": "🍷",
		"title": "Wine Glass"
	},
	{
		"emj": "🍸",
		"title": "Cocktail Glass"
	},
	{
		"emj": "🍹",
		"title": "Tropical Drink"
	},
	{
		"emj": "🍺",
		"title": "Beer Mug"
	},
	{
		"emj": "🍻",
		"title": "Clinking Beer Mugs"
	},
	{
		"emj": "🥂",
		"title": "Clinking Glasses"
	},
	{
		"emj": "🥃",
		"title": "Tumbler Glass"
	},
	{
		"emj": "🥤",
		"title": "Cup With Straw"
	},
	{
		"emj": "🥢",
		"title": "Chopsticks"
	},
	{
		"emj": "🍽",
		"title": "Fork And Knife With Plate"
	},
	{
		"emj": "🍴",
		"title": "Fork And Knife"
	},
	{
		"emj": "🥄",
		"title": "Spoon"
	},
	{
		"emj": "🔪",
		"title": "Kitchen Knife"
	},
	{
		"emj": "🏺",
		"title": "Amphora"
	}
	]
},
{
	"name": "Activities",
	"emojies": [
	{
		"emj": "🎃",
		"title": "Jack-o-lantern"
	},
	{
		"emj": "🎄",
		"title": "Christmas Tree"
	},
	{
		"emj": "🎆",
		"title": "Fireworks"
	},
	{
		"emj": "🎇",
		"title": "Sparkler"
	},
	{
		"emj": "✨",
		"title": "Sparkles"
	},
	{
		"emj": "🎈",
		"title": "Balloon"
	},
	{
		"emj": "🎉",
		"title": "Party Popper"
	},
	{
		"emj": "🎊",
		"title": "Confetti Ball"
	},
	{
		"emj": "🎋",
		"title": "Tanabata Tree"
	},
	{
		"emj": "🎍",
		"title": "Pine Decoration"
	},
	{
		"emj": "🎎",
		"title": "Japanese Dolls"
	},
	{
		"emj": "🎏",
		"title": "Carp Streamer"
	},
	{
		"emj": "🎐",
		"title": "Wind Chime"
	},
	{
		"emj": "🎑",
		"title": "Moon Viewing Ceremony"
	},
	{
		"emj": "🎀",
		"title": "Ribbon"
	},
	{
		"emj": "🎁",
		"title": "Wrapped Gift"
	},
	{
		"emj": "🎗",
		"title": "Reminder Ribbon"
	},
	{
		"emj": "🎟",
		"title": "Admission Tickets"
	},
	{
		"emj": "🎫",
		"title": "Ticket"
	},
	{
		"emj": "🎖",
		"title": "Military Medal"
	},
	{
		"emj": "🏆",
		"title": "Trophy"
	},
	{
		"emj": "🏅",
		"title": "Sports Medal"
	},
	{
		"emj": "🥇",
		"title": "1st Place Medal"
	},
	{
		"emj": "🥈",
		"title": "2nd Place Medal"
	},
	{
		"emj": "🥉",
		"title": "3rd Place Medal"
	},
	{
		"emj": "⚽",
		"title": "Soccer Ball"
	},
	{
		"emj": "⚾",
		"title": "Baseball"
	},
	{
		"emj": "🏀",
		"title": "Basketball"
	},
	{
		"emj": "🏐",
		"title": "Volleyball"
	},
	{
		"emj": "🏈",
		"title": "American Football"
	},
	{
		"emj": "🏉",
		"title": "Rugby Football"
	},
	{
		"emj": "🎾",
		"title": "Tennis"
	},
	{
		"emj": "🎳",
		"title": "Bowling"
	},
	{
		"emj": "🏏",
		"title": "Cricket Game"
	},
	{
		"emj": "🏑",
		"title": "Field Hockey"
	},
	{
		"emj": "🏒",
		"title": "Ice Hockey"
	},
	{
		"emj": "🏓",
		"title": "Ping Pong"
	},
	{
		"emj": "🏸",
		"title": "Badminton"
	},
	{
		"emj": "🥊",
		"title": "Boxing Glove"
	},
	{
		"emj": "🥋",
		"title": "Martial Arts Uniform"
	},
	{
		"emj": "🥅",
		"title": "Goal Net"
	},
	{
		"emj": "⛳",
		"title": "Flag In Hole"
	},
	{
		"emj": "⛸",
		"title": "Ice Skate"
	},
	{
		"emj": "🎣",
		"title": "Fishing Pole"
	},
	{
		"emj": "🎽",
		"title": "Running Shirt"
	},
	{
		"emj": "🎿",
		"title": "Skis"
	},
	{
		"emj": "🛷",
		"title": "Sled"
	},
	{
		"emj": "🥌",
		"title": "Curling Stone"
	},
	{
		"emj": "🎯",
		"title": "Direct Hit"
	},
	{
		"emj": "🎱",
		"title": "Pool 8 Ball"
	},
	{
		"emj": "🔮",
		"title": "Crystal Ball"
	},
	{
		"emj": "🎮",
		"title": "Video Game"
	},
	{
		"emj": "🕹",
		"title": "Joystick"
	},
	{
		"emj": "🎰",
		"title": "Slot Machine"
	},
	{
		"emj": "🎲",
		"title": "Game Die"
	},
	{
		"emj": "♠",
		"title": "Spade Suit"
	},
	{
		"emj": "♥",
		"title": "Heart Suit"
	},
	{
		"emj": "♦",
		"title": "Diamond Suit"
	},
	{
		"emj": "♣",
		"title": "Club Suit"
	},
	{
		"emj": "🃏",
		"title": "Joker"
	},
	{
		"emj": "🀄",
		"title": "Mahjong Red Dragon"
	},
	{
		"emj": "🎴",
		"title": "Flower Playing Cards"
	},
	{
		"emj": "🎭",
		"title": "Performing Arts"
	},
	{
		"emj": "🖼",
		"title": "Framed Picture"
	},
	{
		"emj": "🎨",
		"title": "Artist Palette"
	}
	]
},
{
	"name": "Travel & Places",
	"emojies": [
	{
		"emj": "🌍",
		"title": "Globe Showing Europe-Africa"
	},
	{
		"emj": "🌎",
		"title": "Globe Showing Americas"
	},
	{
		"emj": "🌏",
		"title": "Globe Showing Asia-Australia"
	},
	{
		"emj": "🌐",
		"title": "Globe With Meridians"
	},
	{
		"emj": "🗺",
		"title": "World Map"
	},
	{
		"emj": "🗾",
		"title": "Map Of Japan"
	},
	{
		"emj": "🏔",
		"title": "Snow-capped Mountain"
	},
	{
		"emj": "⛰",
		"title": "Mountain"
	},
	{
		"emj": "🌋",
		"title": "Volcano"
	},
	{
		"emj": "🗻",
		"title": "Mount Fuji"
	},
	{
		"emj": "🏕",
		"title": "Camping"
	},
	{
		"emj": "🏖",
		"title": "Beach With Umbrella"
	},
	{
		"emj": "🏜",
		"title": "Desert"
	},
	{
		"emj": "🏝",
		"title": "Desert Island"
	},
	{
		"emj": "🏞",
		"title": "National Park"
	},
	{
		"emj": "🏟",
		"title": "Stadium"
	},
	{
		"emj": "🏛",
		"title": "Classical Building"
	},
	{
		"emj": "🏗",
		"title": "Building Construction"
	},
	{
		"emj": "🏘",
		"title": "Houses"
	},
	{
		"emj": "🏚",
		"title": "Derelict House"
	},
	{
		"emj": "🏠",
		"title": "House"
	},
	{
		"emj": "🏡",
		"title": "House With Garden"
	},
	{
		"emj": "🏢",
		"title": "Office Building"
	},
	{
		"emj": "🏣",
		"title": "Japanese Post Office"
	},
	{
		"emj": "🏤",
		"title": "Post Office"
	},
	{
		"emj": "🏥",
		"title": "Hospital"
	},
	{
		"emj": "🏦",
		"title": "Bank"
	},
	{
		"emj": "🏨",
		"title": "Hotel"
	},
	{
		"emj": "🏩",
		"title": "Love Hotel"
	},
	{
		"emj": "🏪",
		"title": "Convenience Store"
	},
	{
		"emj": "🏫",
		"title": "School"
	},
	{
		"emj": "🏬",
		"title": "Department Store"
	},
	{
		"emj": "🏭",
		"title": "Factory"
	},
	{
		"emj": "🏯",
		"title": "Japanese Castle"
	},
	{
		"emj": "🏰",
		"title": "Castle"
	},
	{
		"emj": "💒",
		"title": "Wedding"
	},
	{
		"emj": "🗼",
		"title": "Tokyo Tower"
	},
	{
		"emj": "🗽",
		"title": "Statue Of Liberty"
	},
	{
		"emj": "⛪",
		"title": "Church"
	},
	{
		"emj": "🕌",
		"title": "Mosque"
	},
	{
		"emj": "🕍",
		"title": "Synagogue"
	},
	{
		"emj": "⛩",
		"title": "Shinto Shrine"
	},
	{
		"emj": "🕋",
		"title": "Kaaba"
	},
	{
		"emj": "⛲",
		"title": "Fountain"
	},
	{
		"emj": "⛺",
		"title": "Tent"
	},
	{
		"emj": "🌁",
		"title": "Foggy"
	},
	{
		"emj": "🌃",
		"title": "Night With Stars"
	},
	{
		"emj": "🏙",
		"title": "Cityscape"
	},
	{
		"emj": "🌄",
		"title": "Sunrise Over Mountains"
	},
	{
		"emj": "🌅",
		"title": "Sunrise"
	},
	{
		"emj": "🌆",
		"title": "Cityscape At Dusk"
	},
	{
		"emj": "🌇",
		"title": "Sunset"
	},
	{
		"emj": "🌉",
		"title": "Bridge At Night"
	},
	{
		"emj": "♨",
		"title": "Hot Springs"
	},
	{
		"emj": "🌌",
		"title": "Milky Way"
	},
	{
		"emj": "🎠",
		"title": "Carousel Horse"
	},
	{
		"emj": "🎡",
		"title": "Ferris Wheel"
	},
	{
		"emj": "🎢",
		"title": "Roller Coaster"
	},
	{
		"emj": "💈",
		"title": "Barber Pole"
	},
	{
		"emj": "🎪",
		"title": "Circus Tent"
	},
	{
		"emj": "🚂",
		"title": "Locomotive"
	},
	{
		"emj": "🚃",
		"title": "Railway Car"
	},
	{
		"emj": "🚄",
		"title": "High-speed Train"
	},
	{
		"emj": "🚅",
		"title": "Bullet Train"
	},
	{
		"emj": "🚆",
		"title": "Train"
	},
	{
		"emj": "🚇",
		"title": "Metro"
	},
	{
		"emj": "🚈",
		"title": "Light Rail"
	},
	{
		"emj": "🚉",
		"title": "Station"
	},
	{
		"emj": "🚊",
		"title": "Tram"
	},
	{
		"emj": "🚝",
		"title": "Monorail"
	},
	{
		"emj": "🚞",
		"title": "Mountain Railway"
	},
	{
		"emj": "🚋",
		"title": "Tram Car"
	},
	{
		"emj": "🚌",
		"title": "Bus"
	},
	{
		"emj": "🚍",
		"title": "Oncoming Bus"
	},
	{
		"emj": "🚎",
		"title": "Trolleybus"
	},
	{
		"emj": "🚐",
		"title": "Minibus"
	},
	{
		"emj": "🚑",
		"title": "Ambulance"
	},
	{
		"emj": "🚒",
		"title": "Fire Engine"
	},
	{
		"emj": "🚓",
		"title": "Police Car"
	},
	{
		"emj": "🚔",
		"title": "Oncoming Police Car"
	},
	{
		"emj": "🚕",
		"title": "Taxi"
	},
	{
		"emj": "🚖",
		"title": "Oncoming Taxi"
	},
	{
		"emj": "🚗",
		"title": "Automobile"
	},
	{
		"emj": "🚘",
		"title": "Oncoming Automobile"
	},
	{
		"emj": "🚙",
		"title": "Sport Utility Vehicle"
	},
	{
		"emj": "🚚",
		"title": "Delivery Truck"
	},
	{
		"emj": "🚛",
		"title": "Articulated Lorry"
	},
	{
		"emj": "🚜",
		"title": "Tractor"
	},
	{
		"emj": "🚲",
		"title": "Bicycle"
	},
	{
		"emj": "🛴",
		"title": "Kick Scooter"
	},
	{
		"emj": "🛵",
		"title": "Motor Scooter"
	},
	{
		"emj": "🚏",
		"title": "Bus Stop"
	},
	{
		"emj": "🛣",
		"title": "Motorway"
	},
	{
		"emj": "🛤",
		"title": "Railway Track"
	},
	{
		"emj": "🛢",
		"title": "Oil Drum"
	},
	{
		"emj": "⛽",
		"title": "Fuel Pump"
	},
	{
		"emj": "🚨",
		"title": "Police Car Light"
	},
	{
		"emj": "🚥",
		"title": "Horizontal Traffic Light"
	},
	{
		"emj": "🚦",
		"title": "Vertical Traffic Light"
	},
	{
		"emj": "🛑",
		"title": "Stop Sign"
	},
	{
		"emj": "🚧",
		"title": "Construction"
	},
	{
		"emj": "⚓",
		"title": "Anchor"
	},
	{
		"emj": "⛵",
		"title": "Sailboat"
	},
	{
		"emj": "🛶",
		"title": "Canoe"
	},
	{
		"emj": "🚤",
		"title": "Speedboat"
	},
	{
		"emj": "🛳",
		"title": "Passenger Ship"
	},
	{
		"emj": "⛴",
		"title": "Ferry"
	},
	{
		"emj": "🛥",
		"title": "Motor Boat"
	},
	{
		"emj": "🚢",
		"title": "Ship"
	},
	{
		"emj": "✈",
		"title": "Airplane"
	},
	{
		"emj": "🛩",
		"title": "Small Airplane"
	},
	{
		"emj": "🛫",
		"title": "Airplane Departure"
	},
	{
		"emj": "🛬",
		"title": "Airplane Arrival"
	},
	{
		"emj": "💺",
		"title": "Seat"
	},
	{
		"emj": "🚁",
		"title": "Helicopter"
	},
	{
		"emj": "🚟",
		"title": "Suspension Railway"
	},
	{
		"emj": "🚠",
		"title": "Mountain Cableway"
	},
	{
		"emj": "🚡",
		"title": "Aerial Tramway"
	},
	{
		"emj": "🛰",
		"title": "Satellite"
	},
	{
		"emj": "🚀",
		"title": "Rocket"
	},
	{
		"emj": "🛸",
		"title": "Flying Saucer"
	},
	{
		"emj": "🛎",
		"title": "Bellhop Bell"
	},
	{
		"emj": "⌛",
		"title": "Hourglass Done"
	},
	{
		"emj": "⏳",
		"title": "Hourglass Not Done"
	},
	{
		"emj": "⌚",
		"title": "Watch"
	},
	{
		"emj": "⏰",
		"title": "Alarm Clock"
	},
	{
		"emj": "⏱",
		"title": "Stopwatch"
	},
	{
		"emj": "⏲",
		"title": "Timer Clock"
	},
	{
		"emj": "🕰",
		"title": "Mantelpiece Clock"
	},
	{
		"emj": "🕛",
		"title": "Twelve O’clock"
	},
	{
		"emj": "🕧",
		"title": "Twelve-thirty"
	},
	{
		"emj": "🕐",
		"title": "One O’clock"
	},
	{
		"emj": "🕜",
		"title": "One-thirty"
	},
	{
		"emj": "🕑",
		"title": "Two O’clock"
	},
	{
		"emj": "🕝",
		"title": "Two-thirty"
	},
	{
		"emj": "🕒",
		"title": "Three O’clock"
	},
	{
		"emj": "🕞",
		"title": "Three-thirty"
	},
	{
		"emj": "🕓",
		"title": "Four O’clock"
	},
	{
		"emj": "🕟",
		"title": "Four-thirty"
	},
	{
		"emj": "🕔",
		"title": "Five O’clock"
	},
	{
		"emj": "🕠",
		"title": "Five-thirty"
	},
	{
		"emj": "🕕",
		"title": "Six O’clock"
	},
	{
		"emj": "🕡",
		"title": "Six-thirty"
	},
	{
		"emj": "🕖",
		"title": "Seven O’clock"
	},
	{
		"emj": "🕢",
		"title": "Seven-thirty"
	},
	{
		"emj": "🕗",
		"title": "Eight O’clock"
	},
	{
		"emj": "🕣",
		"title": "Eight-thirty"
	},
	{
		"emj": "🕘",
		"title": "Nine O’clock"
	},
	{
		"emj": "🕤",
		"title": "Nine-thirty"
	},
	{
		"emj": "🕙",
		"title": "Ten O’clock"
	},
	{
		"emj": "🕥",
		"title": "Ten-thirty"
	},
	{
		"emj": "🕚",
		"title": "Eleven O’clock"
	},
	{
		"emj": "🕦",
		"title": "Eleven-thirty"
	},
	{
		"emj": "🌑",
		"title": "New Moon"
	},
	{
		"emj": "🌒",
		"title": "Waxing Crescent Moon"
	},
	{
		"emj": "🌓",
		"title": "First Quarter Moon"
	},
	{
		"emj": "🌔",
		"title": "Waxing Gibbous Moon"
	},
	{
		"emj": "🌕",
		"title": "Full Moon"
	},
	{
		"emj": "🌖",
		"title": "Waning Gibbous Moon"
	},
	{
		"emj": "🌗",
		"title": "Last Quarter Moon"
	},
	{
		"emj": "🌘",
		"title": "Waning Crescent Moon"
	},
	{
		"emj": "🌙",
		"title": "Crescent Moon"
	},
	{
		"emj": "🌚",
		"title": "New Moon Face"
	},
	{
		"emj": "🌛",
		"title": "First Quarter Moon Face"
	},
	{
		"emj": "🌜",
		"title": "Last Quarter Moon Face"
	},
	{
		"emj": "🌡",
		"title": "Thermometer"
	},
	{
		"emj": "☀",
		"title": "Sun"
	},
	{
		"emj": "🌝",
		"title": "Full Moon Face"
	},
	{
		"emj": "🌞",
		"title": "Sun With Face"
	},
	{
		"emj": "⭐",
		"title": "Star"
	},
	{
		"emj": "🌟",
		"title": "Glowing Star"
	},
	{
		"emj": "🌠",
		"title": "Shooting Star"
	},
	{
		"emj": "☁",
		"title": "Cloud"
	},
	{
		"emj": "⛅",
		"title": "Sun Behind Cloud"
	},
	{
		"emj": "⛈",
		"title": "Cloud With Lightning And Rain"
	},
	{
		"emj": "🌤",
		"title": "Sun Behind Small Cloud"
	},
	{
		"emj": "🌥",
		"title": "Sun Behind Large Cloud"
	},
	{
		"emj": "🌦",
		"title": "Sun Behind Rain Cloud"
	},
	{
		"emj": "🌧",
		"title": "Cloud With Rain"
	},
	{
		"emj": "🌨",
		"title": "Cloud With Snow"
	},
	{
		"emj": "🌩",
		"title": "Cloud With Lightning"
	},
	{
		"emj": "🌪",
		"title": "Tornado"
	},
	{
		"emj": "🌫",
		"title": "Fog"
	},
	{
		"emj": "🌬",
		"title": "Wind Face"
	},
	{
		"emj": "🌀",
		"title": "Cyclone"
	},
	{
		"emj": "🌈",
		"title": "Rainbow"
	},
	{
		"emj": "🌂",
		"title": "Closed Umbrella"
	},
	{
		"emj": "☂",
		"title": "Umbrella"
	},
	{
		"emj": "☔",
		"title": "Umbrella With Rain Drops"
	},
	{
		"emj": "⛱",
		"title": "Umbrella On Ground"
	},
	{
		"emj": "⚡",
		"title": "High Voltage"
	},
	{
		"emj": "❄",
		"title": "Snowflake"
	},
	{
		"emj": "☃",
		"title": "Snowman"
	},
	{
		"emj": "⛄",
		"title": "Snowman Without Snow"
	},
	{
		"emj": "☄",
		"title": "Comet"
	},
	{
		"emj": "🔥",
		"title": "Fire"
	},
	{
		"emj": "💧",
		"title": "Droplet"
	},
	{
		"emj": "🌊",
		"title": "Water Wave"
	}
	]
},
{
	"name": "Objects",
	"emojies": [
	{
		"emj": "🔇",
		"title": "Muted Speaker"
	},
	{
		"emj": "🔈",
		"title": "Speaker Low Volume"
	},
	{
		"emj": "🔉",
		"title": "Speaker Medium Volume"
	},
	{
		"emj": "🔊",
		"title": "Speaker High Volume"
	},
	{
		"emj": "📢",
		"title": "Loudspeaker"
	},
	{
		"emj": "📣",
		"title": "Megaphone"
	},
	{
		"emj": "📯",
		"title": "Postal Horn"
	},
	{
		"emj": "🔔",
		"title": "Bell"
	},
	{
		"emj": "🔕",
		"title": "Bell With Slash"
	},
	{
		"emj": "🎼",
		"title": "Musical Score"
	},
	{
		"emj": "🎵",
		"title": "Musical Note"
	},
	{
		"emj": "🎶",
		"title": "Musical Notes"
	},
	{
		"emj": "🎙",
		"title": "Studio Microphone"
	},
	{
		"emj": "🎚",
		"title": "Level Slider"
	},
	{
		"emj": "🎛",
		"title": "Control Knobs"
	},
	{
		"emj": "🎤",
		"title": "Microphone"
	},
	{
		"emj": "🎧",
		"title": "Headphone"
	},
	{
		"emj": "📻",
		"title": "Radio"
	},
	{
		"emj": "🎷",
		"title": "Saxophone"
	},
	{
		"emj": "🎸",
		"title": "Guitar"
	},
	{
		"emj": "🎹",
		"title": "Musical Keyboard"
	},
	{
		"emj": "🎺",
		"title": "Trumpet"
	},
	{
		"emj": "🎻",
		"title": "Violin"
	},
	{
		"emj": "🥁",
		"title": "Drum"
	},
	{
		"emj": "📱",
		"title": "Mobile Phone"
	},
	{
		"emj": "📲",
		"title": "Mobile Phone With Arrow"
	},
	{
		"emj": "☎",
		"title": "Telephone"
	},
	{
		"emj": "📞",
		"title": "Telephone Receiver"
	},
	{
		"emj": "📟",
		"title": "Pager"
	},
	{
		"emj": "📠",
		"title": "Fax Machine"
	},
	{
		"emj": "🔋",
		"title": "Battery"
	},
	{
		"emj": "🔌",
		"title": "Electric Plug"
	},
	{
		"emj": "💻",
		"title": "Laptop Computer"
	},
	{
		"emj": "🖥",
		"title": "Desktop Computer"
	},
	{
		"emj": "🖨",
		"title": "Printer"
	},
	{
		"emj": "⌨",
		"title": "Keyboard"
	},
	{
		"emj": "🖱",
		"title": "Computer Mouse"
	},
	{
		"emj": "🖲",
		"title": "Trackball"
	},
	{
		"emj": "💽",
		"title": "Computer Disk"
	},
	{
		"emj": "💾",
		"title": "Floppy Disk"
	},
	{
		"emj": "💿",
		"title": "Optical Disk"
	},
	{
		"emj": "📀",
		"title": "Dvd"
	},
	{
		"emj": "🎥",
		"title": "Movie Camera"
	},
	{
		"emj": "🎞",
		"title": "Film Frames"
	},
	{
		"emj": "📽",
		"title": "Film Projector"
	},
	{
		"emj": "🎬",
		"title": "Clapper Board"
	},
	{
		"emj": "📺",
		"title": "Television"
	},
	{
		"emj": "📷",
		"title": "Camera"
	},
	{
		"emj": "📸",
		"title": "Camera With Flash"
	},
	{
		"emj": "📹",
		"title": "Video Camera"
	},
	{
		"emj": "📼",
		"title": "Videocassette"
	},
	{
		"emj": "🔍",
		"title": "Magnifying Glass Tilted Left"
	},
	{
		"emj": "🔎",
		"title": "Magnifying Glass Tilted Right"
	},
	{
		"emj": "🕯",
		"title": "Candle"
	},
	{
		"emj": "💡",
		"title": "Light Bulb"
	},
	{
		"emj": "🔦",
		"title": "Flashlight"
	},
	{
		"emj": "🏮",
		"title": "Red Paper Lantern"
	},
	{
		"emj": "📔",
		"title": "Notebook With Decorative Cover"
	},
	{
		"emj": "📕",
		"title": "Closed Book"
	},
	{
		"emj": "📖",
		"title": "Open Book"
	},
	{
		"emj": "📗",
		"title": "Green Book"
	},
	{
		"emj": "📘",
		"title": "Blue Book"
	},
	{
		"emj": "📙",
		"title": "Orange Book"
	},
	{
		"emj": "📚",
		"title": "Books"
	},
	{
		"emj": "📓",
		"title": "Notebook"
	},
	{
		"emj": "📒",
		"title": "Ledger"
	},
	{
		"emj": "📃",
		"title": "Page With Curl"
	},
	{
		"emj": "📜",
		"title": "Scroll"
	},
	{
		"emj": "📄",
		"title": "Page Facing Up"
	},
	{
		"emj": "📰",
		"title": "Newspaper"
	},
	{
		"emj": "🗞",
		"title": "Rolled-up Newspaper"
	},
	{
		"emj": "📑",
		"title": "Bookmark Tabs"
	},
	{
		"emj": "🔖",
		"title": "Bookmark"
	},
	{
		"emj": "🏷",
		"title": "Label"
	},
	{
		"emj": "💰",
		"title": "Money Bag"
	},
	{
		"emj": "💴",
		"title": "Yen Banknote"
	},
	{
		"emj": "💵",
		"title": "Dollar Banknote"
	},
	{
		"emj": "💶",
		"title": "Euro Banknote"
	},
	{
		"emj": "💷",
		"title": "Pound Banknote"
	},
	{
		"emj": "💸",
		"title": "Money With Wings"
	},
	{
		"emj": "💳",
		"title": "Credit Card"
	},
	{
		"emj": "💹",
		"title": "Chart Increasing With Yen"
	},
	{
		"emj": "💱",
		"title": "Currency Exchange"
	},
	{
		"emj": "💲",
		"title": "Heavy Dollar Sign"
	},
	{
		"emj": "✉",
		"title": "Envelope"
	},
	{
		"emj": "📧",
		"title": "E-mail"
	},
	{
		"emj": "📨",
		"title": "Incoming Envelope"
	},
	{
		"emj": "📩",
		"title": "Envelope With Arrow"
	},
	{
		"emj": "📤",
		"title": "Outbox Tray"
	},
	{
		"emj": "📥",
		"title": "Inbox Tray"
	},
	{
		"emj": "📦",
		"title": "Package"
	},
	{
		"emj": "📫",
		"title": "Closed Mailbox With Raised Flag"
	},
	{
		"emj": "📪",
		"title": "Closed Mailbox With Lowered Flag"
	},
	{
		"emj": "📬",
		"title": "Open Mailbox With Raised Flag"
	},
	{
		"emj": "📭",
		"title": "Open Mailbox With Lowered Flag"
	},
	{
		"emj": "📮",
		"title": "Postbox"
	},
	{
		"emj": "🗳",
		"title": "Ballot Box With Ballot"
	},
	{
		"emj": "✏",
		"title": "Pencil"
	},
	{
		"emj": "✒",
		"title": "Black Nib"
	},
	{
		"emj": "🖋",
		"title": "Fountain Pen"
	},
	{
		"emj": "🖊",
		"title": "Pen"
	},
	{
		"emj": "🖌",
		"title": "Paintbrush"
	},
	{
		"emj": "🖍",
		"title": "Crayon"
	},
	{
		"emj": "📝",
		"title": "Memo"
	},
	{
		"emj": "💼",
		"title": "Briefcase"
	},
	{
		"emj": "📁",
		"title": "File Folder"
	},
	{
		"emj": "📂",
		"title": "Open File Folder"
	},
	{
		"emj": "🗂",
		"title": "Card Index Dividers"
	},
	{
		"emj": "📅",
		"title": "Calendar"
	},
	{
		"emj": "📆",
		"title": "Tear-off Calendar"
	},
	{
		"emj": "🗒",
		"title": "Spiral Notepad"
	},
	{
		"emj": "🗓",
		"title": "Spiral Calendar"
	},
	{
		"emj": "📇",
		"title": "Card Index"
	},
	{
		"emj": "📈",
		"title": "Chart Increasing"
	},
	{
		"emj": "📉",
		"title": "Chart Decreasing"
	},
	{
		"emj": "📊",
		"title": "Bar Chart"
	},
	{
		"emj": "📋",
		"title": "Clipboard"
	},
	{
		"emj": "📌",
		"title": "Pushpin"
	},
	{
		"emj": "📍",
		"title": "Round Pushpin"
	},
	{
		"emj": "📎",
		"title": "Paperclip"
	},
	{
		"emj": "🖇",
		"title": "Linked Paperclips"
	},
	{
		"emj": "📏",
		"title": "Straight Ruler"
	},
	{
		"emj": "📐",
		"title": "Triangular Ruler"
	},
	{
		"emj": "✂",
		"title": "Scissors"
	},
	{
		"emj": "🗃",
		"title": "Card File Box"
	},
	{
		"emj": "🗄",
		"title": "File Cabinet"
	},
	{
		"emj": "🗑",
		"title": "Wastebasket"
	},
	{
		"emj": "🔒",
		"title": "Locked"
	},
	{
		"emj": "🔓",
		"title": "Unlocked"
	},
	{
		"emj": "🔏",
		"title": "Locked With Pen"
	},
	{
		"emj": "🔐",
		"title": "Locked With Key"
	},
	{
		"emj": "🔑",
		"title": "Key"
	},
	{
		"emj": "🗝",
		"title": "Old Key"
	},
	{
		"emj": "🔨",
		"title": "Hammer"
	},
	{
		"emj": "⛏",
		"title": "Pick"
	},
	{
		"emj": "⚒",
		"title": "Hammer And Pick"
	},
	{
		"emj": "🛠",
		"title": "Hammer And Wrench"
	},
	{
		"emj": "🗡",
		"title": "Dagger"
	},
	{
		"emj": "⚔",
		"title": "Crossed Swords"
	},
	{
		"emj": "🔫",
		"title": "Pistol"
	},
	{
		"emj": "🏹",
		"title": "Bow And Arrow"
	},
	{
		"emj": "🛡",
		"title": "Shield"
	},
	{
		"emj": "🔧",
		"title": "Wrench"
	},
	{
		"emj": "🔩",
		"title": "Nut And Bolt"
	},
	{
		"emj": "⚙",
		"title": "Gear"
	},
	{
		"emj": "🗜",
		"title": "Clamp"
	},
	{
		"emj": "⚖",
		"title": "Balance Scale"
	},
	{
		"emj": "🔗",
		"title": "Link"
	},
	{
		"emj": "⛓",
		"title": "Chains"
	},
	{
		"emj": "⚗",
		"title": "Alembic"
	},
	{
		"emj": "🔬",
		"title": "Microscope"
	},
	{
		"emj": "🔭",
		"title": "Telescope"
	},
	{
		"emj": "📡",
		"title": "Satellite Antenna"
	},
	{
		"emj": "💉",
		"title": "Syringe"
	},
	{
		"emj": "💊",
		"title": "Pill"
	},
	{
		"emj": "🚪",
		"title": "Door"
	},
	{
		"emj": "🛏",
		"title": "Bed"
	},
	{
		"emj": "🛋",
		"title": "Couch And Lamp"
	},
	{
		"emj": "🚽",
		"title": "Toilet"
	},
	{
		"emj": "🚿",
		"title": "Shower"
	},
	{
		"emj": "🛁",
		"title": "Bathtub"
	},
	{
		"emj": "🛒",
		"title": "Shopping Cart"
	},
	{
		"emj": "🚬",
		"title": "Cigarette"
	},
	{
		"emj": "⚰",
		"title": "Coffin"
	},
	{
		"emj": "⚱",
		"title": "Funeral Urn"
	},
	{
		"emj": "🗿",
		"title": "Moai"
	}
	]
},{
	"name": "Symbols",
	"emojies":[
	{
		"emj": "🏧",
		"title": "ATM Sign"
	},
	{
		"emj": "🚮",
		"title": "Litter In Bin Sign"
	},
	{
		"emj": "🚰",
		"title": "Potable Water"
	},
	{
		"emj": "♿",
		"title": "Wheelchair Symbol"
	},
	{
		"emj": "🚹",
		"title": "Men’s Room"
	},
	{
		"emj": "🚺",
		"title": "Women’s Room"
	},
	{
		"emj": "🚻",
		"title": "Restroom"
	},
	{
		"emj": "🚼",
		"title": "Baby Symbol"
	},
	{
		"emj": "🚾",
		"title": "Water Closet"
	},
	{
		"emj": "🛂",
		"title": "Passport Control"
	},
	{
		"emj": "🛃",
		"title": "Customs"
	},
	{
		"emj": "🛄",
		"title": "Baggage Claim"
	},
	{
		"emj": "🛅",
		"title": "Left Luggage"
	},
	{
		"emj": "⚠",
		"title": "Warning"
	},
	{
		"emj": "🚸",
		"title": "Children Crossing"
	},
	{
		"emj": "⛔",
		"title": "No Entry"
	},
	{
		"emj": "🚫",
		"title": "Prohibited"
	},
	{
		"emj": "🚳",
		"title": "No Bicycles"
	},
	{
		"emj": "🚭",
		"title": "No Smoking"
	},
	{
		"emj": "🚯",
		"title": "No Littering"
	},
	{
		"emj": "🚱",
		"title": "Non-potable Water"
	},
	{
		"emj": "🚷",
		"title": "No Pedestrians"
	},
	{
		"emj": "📵",
		"title": "No Mobile Phones"
	},
	{
		"emj": "🔞",
		"title": "No One Under Eighteen"
	},
	{
		"emj": "☢",
		"title": "Radioactive"
	},
	{
		"emj": "☣",
		"title": "Biohazard"
	},
	{
		"emj": "⬆",
		"title": "Up Arrow"
	},
	{
		"emj": "↗",
		"title": "Up-right Arrow"
	},
	{
		"emj": "➡",
		"title": "Right Arrow"
	},
	{
		"emj": "↘",
		"title": "Down-right Arrow"
	},
	{
		"emj": "⬇",
		"title": "Down Arrow"
	},
	{
		"emj": "↙",
		"title": "Down-left Arrow"
	},
	{
		"emj": "⬅",
		"title": "Left Arrow"
	},
	{
		"emj": "↖",
		"title": "Up-left Arrow"
	},
	{
		"emj": "↕",
		"title": "Up-down Arrow"
	},
	{
		"emj": "↔",
		"title": "Left-right Arrow"
	},
	{
		"emj": "↩",
		"title": "Right Arrow Curving Left"
	},
	{
		"emj": "↪",
		"title": "Left Arrow Curving Right"
	},
	{
		"emj": "⤴",
		"title": "Right Arrow Curving Up"
	},
	{
		"emj": "⤵",
		"title": "Right Arrow Curving Down"
	},
	{
		"emj": "🔃",
		"title": "Clockwise Vertical Arrows"
	},
	{
		"emj": "🔄",
		"title": "Counterclockwise Arrows Button"
	},
	{
		"emj": "🔙",
		"title": "BACK Arrow"
	},
	{
		"emj": "🔚",
		"title": "END Arrow"
	},
	{
		"emj": "🔛",
		"title": "ON! Arrow"
	},
	{
		"emj": "🔜",
		"title": "SOON Arrow"
	},
	{
		"emj": "🔝",
		"title": "TOP Arrow"
	},
	{
		"emj": "🛐",
		"title": "Place Of Worship"
	},
	{
		"emj": "⚛",
		"title": "Atom Symbol"
	},
	{
		"emj": "🕉",
		"title": "Om"
	},
	{
		"emj": "✡",
		"title": "Star Of David"
	},
	{
		"emj": "☸",
		"title": "Wheel Of Dharma"
	},
	{
		"emj": "☯",
		"title": "Yin Yang"
	},
	{
		"emj": "✝",
		"title": "Latin Cross"
	},
	{
		"emj": "☦",
		"title": "Orthodox Cross"
	},
	{
		"emj": "☪",
		"title": "Star And Crescent"
	},
	{
		"emj": "☮",
		"title": "Peace Symbol"
	},
	{
		"emj": "🕎",
		"title": "Menorah"
	},
	{
		"emj": "🔯",
		"title": "Dotted Six-pointed Star"
	},
	{
		"emj": "♈",
		"title": "Aries"
	},
	{
		"emj": "♉",
		"title": "Taurus"
	},
	{
		"emj": "♊",
		"title": "Gemini"
	},
	{
		"emj": "♋",
		"title": "Cancer"
	},
	{
		"emj": "♌",
		"title": "Leo"
	},
	{
		"emj": "♍",
		"title": "Virgo"
	},
	{
		"emj": "♎",
		"title": "Libra"
	},
	{
		"emj": "♏",
		"title": "Scorpio"
	},
	{
		"emj": "♐",
		"title": "Sagittarius"
	},
	{
		"emj": "♑",
		"title": "Capricorn"
	},
	{
		"emj": "♒",
		"title": "Aquarius"
	},
	{
		"emj": "♓",
		"title": "Pisces"
	},
	{
		"emj": "⛎",
		"title": "Ophiuchus"
	},
	{
		"emj": "🔀",
		"title": "Shuffle Tracks Button"
	},
	{
		"emj": "🔁",
		"title": "Repeat Button"
	},
	{
		"emj": "🔂",
		"title": "Repeat Single Button"
	},
	{
		"emj": "▶",
		"title": "Play Button"
	},
	{
		"emj": "⏩",
		"title": "Fast-forward Button"
	},
	{
		"emj": "⏭",
		"title": "Next Track Button"
	},
	{
		"emj": "⏯",
		"title": "Play Or Pause Button"
	},
	{
		"emj": "◀",
		"title": "Reverse Button"
	},
	{
		"emj": "⏪",
		"title": "Fast Reverse Button"
	},
	{
		"emj": "⏮",
		"title": "Last Track Button"
	},
	{
		"emj": "🔼",
		"title": "Upwards Button"
	},
	{
		"emj": "⏫",
		"title": "Fast Up Button"
	},
	{
		"emj": "🔽",
		"title": "Downwards Button"
	},
	{
		"emj": "⏬",
		"title": "Fast Down Button"
	},
	{
		"emj": "⏸",
		"title": "Pause Button"
	},
	{
		"emj": "⏹",
		"title": "Stop Button"
	},
	{
		"emj": "⏺",
		"title": "Record Button"
	},
	{
		"emj": "⏏",
		"title": "Eject Button"
	},
	{
		"emj": "🎦",
		"title": "Cinema"
	},
	{
		"emj": "🔅",
		"title": "Dim Button"
	},
	{
		"emj": "🔆",
		"title": "Bright Button"
	},
	{
		"emj": "📶",
		"title": "Antenna Bars"
	},
	{
		"emj": "📳",
		"title": "Vibration Mode"
	},
	{
		"emj": "📴",
		"title": "Mobile Phone Off"
	},
	{
		"emj": "♀",
		"title": "Female Sign"
	},
	{
		"emj": "♂",
		"title": "Male Sign"
	},
	{
		"emj": "⚕",
		"title": "Medical Symbol"
	},
	{
		"emj": "♻",
		"title": "Recycling Symbol"
	},
	{
		"emj": "⚜",
		"title": "Fleur-de-lis"
	},
	{
		"emj": "🔱",
		"title": "Trident Emblem"
	},
	{
		"emj": "📛",
		"title": "Name Badge"
	},
	{
		"emj": "🔰",
		"title": "Japanese Symbol For Beginner"
	},
	{
		"emj": "⭕",
		"title": "Heavy Large Circle"
	},
	{
		"emj": "✅",
		"title": "White Heavy Check Mark"
	},
	{
		"emj": "☑",
		"title": "Ballot Box With Check"
	},
	{
		"emj": "✔",
		"title": "Heavy Check Mark"
	},
	{
		"emj": "✖",
		"title": "Heavy Multiplication X"
	},
	{
		"emj": "❌",
		"title": "Cross Mark"
	},
	{
		"emj": "❎",
		"title": "Cross Mark Button"
	},
	{
		"emj": "➕",
		"title": "Heavy Plus Sign"
	},
	{
		"emj": "➖",
		"title": "Heavy Minus Sign"
	},
	{
		"emj": "➗",
		"title": "Heavy Division Sign"
	},
	{
		"emj": "➰",
		"title": "Curly Loop"
	},
	{
		"emj": "➿",
		"title": "Double Curly Loop"
	},
	{
		"emj": "〽",
		"title": "Part Alternation Mark"
	},
	{
		"emj": "✳",
		"title": "Eight-spoked Asterisk"
	},
	{
		"emj": "✴",
		"title": "Eight-pointed Star"
	},
	{
		"emj": "❇",
		"title": "Sparkle"
	},
	{
		"emj": "‼",
		"title": "Double Exclamation Mark"
	},
	{
		"emj": "⁉",
		"title": "Exclamation Question Mark"
	},
	{
		"emj": "❓",
		"title": "Question Mark"
	},
	{
		"emj": "❔",
		"title": "White Question Mark"
	},
	{
		"emj": "❕",
		"title": "White Exclamation Mark"
	},
	{
		"emj": "❗",
		"title": "Exclamation Mark"
	},
	{
		"emj": "〰",
		"title": "Wavy Dash"
	},
	{
		"emj": "©",
		"title": "Copyright"
	},
	{
		"emj": "®",
		"title": "Registered"
	},
	{
		"emj": "™",
		"title": "Trade Mark"
	},
	{
		"emj": "#️⃣",
		"title": "Keycap_#"
	},
	{
		"emj": "*️⃣",
		"title": "Keycap_*"
	},
	{
		"emj": "0️⃣",
		"title": "Keycap_0"
	},
	{
		"emj": "1️⃣",
		"title": "Keycap_1"
	},
	{
		"emj": "2️⃣",
		"title": "Keycap_2"
	},
	{
		"emj": "3️⃣",
		"title": "Keycap_3"
	},
	{
		"emj": "4️⃣",
		"title": "Keycap_4"
	},
	{
		"emj": "5️⃣",
		"title": "Keycap_5"
	},
	{
		"emj": "6️⃣",
		"title": "Keycap_6"
	},
	{
		"emj": "7️⃣",
		"title": "Keycap_7"
	},
	{
		"emj": "8️⃣",
		"title": "Keycap_8"
	},
	{
		"emj": "9️⃣",
		"title": "Keycap_9"
	},
	{
		"emj": "🔟",
		"title": "Keycap_10"
	},
	{
		"emj": "💯",
		"title": "Hundred Points"
	},
	{
		"emj": "🔠",
		"title": "Input Latin Uppercase"
	},
	{
		"emj": "🔡",
		"title": "Input Latin Lowercase"
	},
	{
		"emj": "🔢",
		"title": "Input Numbers"
	},
	{
		"emj": "🔣",
		"title": "Input Symbols"
	},
	{
		"emj": "🔤",
		"title": "Input Latin Letters"
	},
	{
		"emj": "🅰",
		"title": "A Button (blood Type)"
	},
	{
		"emj": "🆎",
		"title": "AB Button (blood Type)"
	},
	{
		"emj": "🅱",
		"title": "B Button (blood Type)"
	},
	{
		"emj": "🆑",
		"title": "CL Button"
	},
	{
		"emj": "🆒",
		"title": "COOL Button"
	},
	{
		"emj": "🆓",
		"title": "FREE Button"
	},
	{
		"emj": "ℹ",
		"title": "Information"
	},
	{
		"emj": "🆔",
		"title": "ID Button"
	},
	{
		"emj": "Ⓜ",
		"title": "Circled M"
	},
	{
		"emj": "🆕",
		"title": "NEW Button"
	},
	{
		"emj": "🆖",
		"title": "NG Button"
	},
	{
		"emj": "🅾",
		"title": "O Button (blood Type)"
	},
	{
		"emj": "🆗",
		"title": "OK Button"
	},
	{
		"emj": "🅿",
		"title": "P Button"
	},
	{
		"emj": "🆘",
		"title": "SOS Button"
	},
	{
		"emj": "🆙",
		"title": "UP! Button"
	},
	{
		"emj": "🆚",
		"title": "VS Button"
	},
	{
		"emj": "🈁",
		"title": "Japanese Here Button"
	},
	{
		"emj": "🈂",
		"title": "Japanese Service Charge Button"
	},
	{
		"emj": "🈷",
		"title": "Japanese Monthly Amount Button"
	},
	{
		"emj": "🈶",
		"title": "Japanese Not Free Of Charge Button"
	},
	{
		"emj": "🈯",
		"title": "Japanese Reserved Button"
	},
	{
		"emj": "🉐",
		"title": "Japanese Bargain Button"
	},
	{
		"emj": "🈹",
		"title": "Japanese Discount Button"
	},
	{
		"emj": "🈚",
		"title": "Japanese Free Of Charge Button"
	},
	{
		"emj": "🈲",
		"title": "Japanese Prohibited Button"
	},
	{
		"emj": "🉑",
		"title": "Japanese Acceptable Button"
	},
	{
		"emj": "🈸",
		"title": "Japanese Application Button"
	},
	{
		"emj": "🈴",
		"title": "Japanese Passing Grade Button"
	},
	{
		"emj": "🈳",
		"title": "Japanese Vacancy Button"
	},
	{
		"emj": "㊗",
		"title": "Japanese Congratulations Button"
	},
	{
		"emj": "㊙",
		"title": "Japanese Secret Button"
	},
	{
		"emj": "🈺",
		"title": "Japanese Open For Business Button"
	},
	{
		"emj": "🈵",
		"title": "Japanese No Vacancy Button"
	},
	{
		"emj": "▪",
		"title": "Black Small Square"
	},
	{
		"emj": "▫",
		"title": "White Small Square"
	},
	{
		"emj": "◻",
		"title": "White Medium Square"
	},
	{
		"emj": "◼",
		"title": "Black Medium Square"
	},
	{
		"emj": "◽",
		"title": "White Medium-small Square"
	},
	{
		"emj": "◾",
		"title": "Black Medium-small Square"
	},
	{
		"emj": "⬛",
		"title": "Black Large Square"
	},
	{
		"emj": "⬜",
		"title": "White Large Square"
	},
	{
		"emj": "🔶",
		"title": "Large Orange Diamond"
	},
	{
		"emj": "🔷",
		"title": "Large Blue Diamond"
	},
	{
		"emj": "🔸",
		"title": "Small Orange Diamond"
	},
	{
		"emj": "🔹",
		"title": "Small Blue Diamond"
	},
	{
		"emj": "🔺",
		"title": "Red Triangle Pointed Up"
	},
	{
		"emj": "🔻",
		"title": "Red Triangle Pointed Down"
	},
	{
		"emj": "💠",
		"title": "Diamond With A Dot"
	},
	{
		"emj": "🔘",
		"title": "Radio Button"
	},
	{
		"emj": "🔲",
		"title": "Black Square Button"
	},
	{
		"emj": "🔳",
		"title": "White Square Button"
	},
	{
		"emj": "⚪",
		"title": "White Circle"
	},
	{
		"emj": "⚫",
		"title": "Black Circle"
	},
	{
		"emj": "🔴",
		"title": "Red Circle"
	},
	{
		"emj": "🔵",
		"title": "Blue Circle"
	}
	]
}
];


(function (factory) {
	if (typeof define === 'function' && define.amd) {
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		module.exports = factory(require('jquery'));
	} else {
		factory(jQuery);
	}
}(function (e) {
	function convert(textString) {
		textString = textString.replace(/ /g, "-");
		textString = textString.replace(/</g, "");
		textString = textString.replace(/>/g, "");
		textString = textString.replace(/"/g, "");
		textString = textString.replace(/é/g, "");
		textString = textString.replace(/!/g, "");
		textString = textString.replace(/'/, "");
		textString = textString.replace(/£/, "");
		textString = textString.replace(/^/, "");
		textString = textString.replace(/#/, "");
		textString = textString.replace(/$/, "");
		textString = textString.replace(/\+/g, "");
		textString = textString.replace(/%/g, "");
		textString = textString.replace(/½/g, "");
		textString = textString.replace(/&/g, "");
		textString = textString.replace(/\//g, "");
		textString = textString.replace(/{/g, "");
		textString = textString.replace(/\(/g, "");
		textString = textString.replace(/\[/g, "");
		textString = textString.replace(/\)/g, "");
		textString = textString.replace(/]/g, "");
		textString = textString.replace(/=/g, "");
		textString = textString.replace(/}/g, "");
		textString = textString.replace(/\?/g, "");
		textString = textString.replace(/\*/g, "");
		textString = textString.replace(/@/g, "");
		textString = textString.replace(/€/g, "");
		textString = textString.replace(/~/g, "");
		textString = textString.replace(/æ/g, "");
		textString = textString.replace(/ß/g, "");
		textString = textString.replace(/;/g, "");
		textString = textString.replace(/,/g, "");
		textString = textString.replace(/`/g, "");
		textString = textString.replace(/|/g, "");
		textString = textString.replace(/\./g, "");
		textString = textString.replace(/:/g, "");
		textString = textString.replace(/İ/g, "i");
		textString = textString.replace(/I/g, "i");
		textString = textString.replace(/ı/g, "i");
		textString = textString.replace(/ğ/g, "g");
		textString = textString.replace(/Ğ/g, "g");
		textString = textString.replace(/ü/g, "u");
		textString = textString.replace(/Ü/g, "u");
		textString = textString.replace(/ş/g, "s");
		textString = textString.replace(/Ş/g, "s");
		textString = textString.replace(/ö/g, "o");
		textString = textString.replace(/Ö/g, "o");
		textString = textString.replace(/ç/g, "c");
		textString = textString.replace(/Ç/g, "c");
		textString = textString.replace(/--/g, "-");
		textString = textString.replace(/---/g, "-");
		textString = textString.replace(/----/g, "-");
		textString = textString.replace(/----/g, "-");

		return textString.toLowerCase();
	}
	var C = {
		main: ".ms-emoji-main",
		header: ".ms-emoji-header",
		content: ".ms-emoji-content",
		list: ".ms-emoji-wk",
		title: ".ms-emoji-title",
		listemoji: ".ms-emoji-list",
		emoji: ".ms-emoji-button"
	},
	H = {
		main: '<div class="'+C.main.replace(".", "")+'"></div>',
		header: '<div class="'+C.header.replace(".", "")+'"></div>',
		content: '<div class="'+C.content.replace(".", "")+'"></div>',
		list: '<div class="'+C.list.replace(".", "")+'"></div>',
		title: '<div class="'+C.title.replace(".", "")+'">{title}</div>',
		listemoji: '<div class="'+C.listemoji.replace(".", "")+'" data-name="{dataTitle}"></div>',
		emoji: '<button class="'+C.emoji.replace(".", "")+'" data-emoji="{emojiAlt}" data-title="{title}">{emoji}</button>',
	},
	F = {
		A: function(lang){
			/*e.cachedScript = function(url, options ) {
				options = $.extend( options || {}, {
					dataType: "script",
					cache: true,
					url: url
				});
				return e.ajax( options )
			};
			$.cachedScript( "msEmoji."+lang+".js?v=1.0.2" ).done(function(emojies) {
				F.E(msEmo, input);
			})*/F.E(msEmo, input);
		},
		H:function(emj){
			e(C.header).append('<div class="ms-emoji-hed-action"></div>');
			e(".ms-emoji-hed-action").append('<div class="ms-emoji-search"><input type="search" placeholder="'+input+'" class="ms-emoji-search"></div>');
			e(".ms-emoji-hed-action").append('<ul class="ms-emoji-action"><li class="ms-s65"><svg viewBox="0 0 24 24" width="28px" height="28px" x="0" y="0"><path d="M13,4.32L9.31,8,13,11.69,11.69,13,8,9.31,4.31,13,3,11.69,6.69,8,3,4.31,4.31,3,8,6.69,11.68,3Z" class="ms-emoji-close"></path></svg></li></ul>');
			
			e(C.main).append('<ul class="ms-emoji-head"></ul>');
			
		},
		E: function(emojies, input){
			if(emojies.length > 0){
				this.mainClass.append(H.main);
				e(C.main).append(H.header);
				e(C.main).append(H.content);
				e(C.content).append(H.list);
				F.H(input);
				F.Title(emojies);
			}
		},
		Title:function(emoji){
			var em = "";
			$.each(emoji, function(i, emj) {
				em += H.title.replace('{title}', emj.name);
				e(C.list).append(H.title.replace('{title}', emj.name));
				e(C.list).append(H.listemoji.replace('{dataTitle}', convert(emj.name)));
				F.Emoji("[data-name="+convert(emj.name)+"]", emj.emojies)

				e("ul.ms-emoji-head").append(
					'<li class="ms-emoji-head"><button class="ms-emoji-group" data-title="'+convert(emj.name)+'">'+emj.emojies[0].emj+'</button></li>'
					);
				console.log(  )
			})
		},
		Emoji: function(eBlock, emoji){
			e.each(emoji, function(i, moji) {
				e(eBlock).append(
					H.emoji
					.replace('{title}', moji.title)
					.replace('{emoji}', moji.emj)
					.replace('{emojiAlt}', moji.emj) 
					)
			})
		},
		Search: function(){
			e(document).on("keyup", 'input.ms-emoji-search', function(){
				var s = convert(e(this).val());
				console.log(s)
				e('.ms-emoji-button').each(function(){
					var t = convert(e(this).data("title")),
					l = t.indexOf(s) !== -1;
					e(this).toggle(l);
				})     
			})
		},
		Click: function(){
			e(document).on('click', 'button.ms-emoji-group', function(ev) {
				ev.preventDefault(); 

				var h = 0;
				e('.ms-emoji-wk > div').each(function(index, el) {
					h += e(this).height()
				})

				var 
				t = e(this).data("title"),
				tt = e('[data-name="'+t+'"]'),
				s = tt.offset().top,
				eh = tt.height();


				console.log(  s , e('.ms-emoji-wk').scrollTop()  )
				
				e('.ms-emoji-wk').animate({
					scrollTop: s - 85
				});



				var t = e(this).data("title");
				
				e('.ms-emoji-wk').animate({
					scrollTop: e('[data-name="'+t+'"]').offset().top-90
				});
				
				console.log( 
					t,
					e('[data-name="'+t+'"]').offset() 
					)
					return false;
				});
			
		},
		Scroll: function(){
			e('.ms-emoji-wk').scroll( function() {
        var T = e(this);
				var scrollDistance = T.scrollTop();
				$('.ms-emoji-list').each(function(i) {
					if (e(this).position().top <= scrollDistance) {
						e('.navigation a.active').removeClass('active');
						e('.navigation a').eq(i).addClass('active');
					}

				});
			}).scroll();
		},
		Init: function(mainClass, o){ 
			this.lang = o.lang;
			this.input = o.input;
			this.mainClass = mainClass
			F.A(this.lang),F.Click(),F.Scroll(),F.Search();
		}
	};


	$.fn.msEmoji = function(o) {
		try {
			F.Init( $(this), o )
		} catch(e) {}
	};
}));


$('.ms-emoji').msEmoji({
	lang: "en"
});

$(document).on('click', '.ms-emoji-button', function() {

    if (options.pageId === "chat") {

        $editor = $("input[name=message_text]");

    } else if (options.pageId === "post" || options.pageId === "image") {

        $editor = $("input[name=comment_text]");

    } else {

        $editor = $("textarea[name=postText]");
    }

    $editor.val($editor.val() + $(this).text());

    $editor.change();

    if (options.pageId === "chat") {

        $(".btn-emoji-picker").dropdown('toggle');
    }

    return false;
});