const drugData = {
    Ampicillin: {
        generic: "Ampicillin",
        brand: ["Ampexin", "Acmecillin", "Ficillin", "Pen-A"],
        dose: {
            tablet: "100mg/kg/day",
            syrup: "125mg/5ml",
            drops: "125mg/1.25ml"
        },
        notes: "Neonate: 12 hourly, Infant and older: 8 hourly"
    },
    Gentamycin: {
        generic: "Gentamycin",
        brand: ["Gentin", "Genacyn", "Invigen"],
        dose: {
            tablet: "5mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Neonate: Once daily, Infant and older: 8 hourly"
    },
    Amikacin: {
        generic: "Amikacin",
        brand: ["Kacin", "Amibac", "Amistar"],
        dose: {
            tablet: "7.5-15mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Neonate: 12 hourly, Infant and older: 8 hourly. Caution: Renal impairment."
    },
    Ceftazidime: {
        generic: "Ceftazidime",
        brand: ["Tazid", "Zitum", "Serozid", "Sidobac", "Trum-3", "Cefazid"],
        dose: {
            tablet: "100mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Neonate: 12 hourly, Infant and older: 8 hourly. For meningitis: 150mg/kg/day"
    },
    Cefotaxime: {
        generic: "Cefotaxime",
        brand: ["Maxcef", "Cefotime", "Taxim", "Cefotex"],
        dose: {
            tablet: "100mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: 8 hourly. For meningitis: 150mg/kg/day"
    },
    Ceftriaxone: {
        generic: "Ceftriaxone",
        brand: ["Ceftron", "Dicephin", "Roficin", "Oricef", "Arixon", "Axon", "Traxef", "Ceftizone"],
        dose: {
            tablet: "50-100mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Meningitis dose: 100mg/kg/day. Enteric Fever: 75mg/kg/day. Therapeutic Range: Up to 2gm BD"
    },
    Imepenem: {
        generic: "Imepenem",
        brand: ["Imenem", "Cispenam"],
        dose: {
            tablet: "60mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: TDS. Always given after dilution with 10ml of IV fluid."
    },
    Meropenem: {
        generic: "Meropenem",
        brand: ["Spacbac", "I-Penem", "Fulspec", "Ropenam", "Neopenam", "Merocon", "Meropen"],
        dose: {
            tablet: "60mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: TDS. Always given after dilution with 10ml of IV fluid."
    },
    Vancomycin: {
        generic: "Vancomycin",
        brand: ["Vancomycin", "Vanmycin", "Vancomin", "Covan"],
        dose: {
            tablet: "<7 days: 15mg/kg/dose 12 hourly/BD, >7 days: 15mg/kg/dose 8 hourly/TDS",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Always given after dilution with 10ml of IV fluid."
    },
    Flucloxacillin: {
        generic: "Flucloxacillin",
        brand: ["Fluclox", "Phylopen", "Phylopen Forte DS Flux", "Flubex", "Flupen", "Flubac"],
        dose: {
            tablet: "25mg/kg/dose",
            syrup: "125mg/5ml",
            drops: "125mg/1.25ml"
        },
        notes: "Infant: 50mg/kg/day (oral). Duration: QDS"
    },
    Cephradine: {
        generic: "Cephradine",
        brand: ["Dicef", "Cephran"],
        dose: {
            tablet: "50mg/kg/day",
            syrup: "125mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: QDS"
    },
    PiperacillinTazobactam: {
        generic: "Piperacillin+Tazobactam",
        brand: ["Megacillin", "Tazopen"],
        dose: {
            tablet: "50-100mg/kg/dose",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: 8 hourly"
    },
    Amoxicillin: {
        generic: "Amoxicillin",
        brand: ["Moxacil", "Moxin", "Tycil", "Fimoxyl"],
        dose: {
            tablet: "40mg/kg/day",
            syrup: "125mg/5ml",
            drops: "125mg/1.25ml"
        },
        notes: "Duration: TDS"
    },
    AmoxicillinClavulanicAcid: {
        generic: "Amoxicillin+Clavulanic Acid",
        brand: ["Moxaclav", "Fimoxyclav", "Ticlav"],
        dose: {
            tablet: "40mg/kg/day",
            syrup: "156mg/5ml",
            drops: "Not available"
        },
        notes: "Neonate: 30mg/kg/day. Duration: TDS (Neonate: BD)"
    },
    Cefuroxime: {
        generic: "Cefuroxime",
        brand: ["Cefotil", "Kilbac", "Sefur", "Cefobac", "Rofurox", "Sefurox", "Cerox-A", "Furocef"],
        dose: {
            tablet: "20-30mg/kg/day",
            syrup: "125mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: 12 hourly"
    },
    Ciprofloxacin: {
        generic: "Ciprofloxacin",
        brand: ["Ciprocin", "Neofloxin", "Flontin", "Beuflox", "Ciprox", "Cipro-A", "X-bac"],
        dose: {
            tablet: "15-30mg/kg/day",
            syrup: "250mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: BD"
    },
    Azithromycin: {
        generic: "Azithromycin",
        brand: ["Zimax"],
        dose: {
            tablet: "10mg/kg/day",
            syrup: "200mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: OD/BD. Enteric fever: 20mg/kg/day"
    },
    Cefixime: {
        generic: "Cefixime",
        brand: ["Cef-3", "Denvar", "T-Cef", "Ceftid", "Rofixim", "Emixef", "Roxim", "Truso", "Triocim", "Orcef", "Starcef"],
        dose: {
            tablet: "10-20mg/kg/day",
            syrup: "100mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: BD"
    },
    Cefepime: {
        generic: "Cefepime",
        brand: ["Ceftipime", "Maxipime", "Xenim", "Ultrapime"],
        dose: {
            tablet: "≤14 days: 30mg/kg/day, >14 days: 50mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "≤14 days: 12 hourly, >14 days: 12 hourly/8 hourly"
    },
    Cephalexin: {
        generic: "Cephalexin",
        brand: ["Ceftipime", "Cephalen", "Ceporin", "Neorex"],
        dose: {
            tablet: "≤7 days: 25mg/kg/dose, >7 days: 25mg/kg/dose",
            syrup: "125mg/5ml",
            drops: "125mg/1.25ml"
        },
        notes: "≤7 days: 12 hourly, >7 days: 8 hourly"
    },
    Cefpodoxime: {
        generic: "Cefpodoxime",
        brand: ["Vanprox", "Ximprox", "Vercef", "Kidcef", "Roxitil", "Trucef"],
        dose: {
            tablet: "10mg/kg/day",
            syrup: "100mg/5ml",
            drops: "20mg/ml=15 drops"
        },
        notes: "Duration: BD"
    },
    Netilmicin: {
        generic: "Netilmicin",
        brand: ["Netromycin"],
        dose: {
            tablet: "In 1st week: 6mg/kg/day, Later: 7.5-9mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "In 1st week: 12 hourly over 30 min inf. or IV 8 hourly, Later: 8 hourly"
    },
    Colomycin: {
        generic: "Colomycin",
        brand: ["Colistin"],
        dose: {
            tablet: "25000 unit/kg/dose",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: 8 hourly"
    },
    Clindamycin: {
        generic: "Clindamycin",
        brand: ["Clindex", "Clindacin", "Climycin"],
        dose: {
            tablet: "0-14 days: 3-6mg/kg/dose, 15 days-12 yrs: 3-6mg/kg/dose",
            syrup: "75mg/5ml",
            drops: "Not available"
        },
        notes: "0-14 days: 8 hourly/TDS, 15 days-12 yrs: 6 hourly/QDS"
    },
    Erythromycin: {
        generic: "Erythromycin",
        brand: ["Eromycin", "A-mycin", "Erythrox"],
        dose: {
            tablet: "50mg/kg/day",
            syrup: "125mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: QDS"
    },
    Metronidazole: {
        generic: "Metronidazole",
        brand: ["Amodis", "Flamyd", "Flazyl", "Metro", "Menz", "Metryl", "Filmet"],
        dose: {
            tablet: "20-30mg/kg/day",
            syrup: "200mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: TDS"
    },
    Acyclovir: {
        generic: "Acyclovir",
        brand: ["Zovirux", "Xovir", "Virux", "Novirux"],
        dose: {
            tablet: "10mg/kg/dose",
            syrup: "200mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: TDS"
    },
    Ganciclovir: {
        generic: "Ganciclovir",
        brand: ["Cymevene", "Cytovene"],
        dose: {
            tablet: "6mg/kg/dose",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: 12 hourly"
    },
    Fluconazole: {
        generic: "Fluconazole",
        brand: ["Flugal", "Lucan-R", "Nispore", "Omastin", "Candinil", "Conaz", "Flucess", "Flucoder", "Flucon"],
        dose: {
            tablet: "Loading dose 12mg/kg/dose, Maintenance dose ≤14 days: 6mg/kg/dose",
            syrup: "50mg/5ml",
            drops: "Not available"
        },
        notes: "≤14 days: Once every 3 days, 15-27 days: Once every 2 days, ≥28 days: Once daily. For Prophylaxis: 3mg/kg/dose Once every 3 days"
    },
    Nystatin: {
        generic: "Nystatin",
        brand: ["Nystat", "Candex", "Fungistin", "Naf"],
        dose: {
            tablet: "1ml/15 drops",
            syrup: "Not available",
            drops: "100,000 unit/ml"
        },
        notes: "Duration: TDS/QDS"
    },
    Chloroquine: {
        generic: "Chloroquine",
        brand: ["Avloquin", "Jesochlor", "Base"],
        dose: {
            tablet: "10mg/kg on day 1, 5mg/kg on day 2, 5mg/kg on day 3",
            syrup: "80mg/5ml",
            drops: "Not available"
        },
        notes: ""
    },
    Primaquin: {
        generic: "Primaquin",
        brand: ["Primaquin"],
        dose: {
            tablet: "0.9mg/kg",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: Single Dose"
    },
    Cotrimoxazole: {
        generic: "Cotrimoxazole",
        brand: ["Cotrim", "Bactipront"],
        dose: {
            tablet: "8mg/kg/day",
            syrup: "1 TSF= 200mg (S)+ 40mg (T)",
            drops: "Not available"
        },
        notes: "Duration: BD"
    },
    Quinine: {
        generic: "Quinine",
        brand: ["Jasoquin"],
        dose: {
            tablet: "20mg/kg Loading for 4 hours & after 8 hours of Loading, Maintenance 10mg/kg/day in TDS",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Amp: 1 amp=60mg/ml with 10% Dextrose or 5% DA, 1 amp= 300mg/5ml with 100ml 5% or 10% DA"
    },
    Pentaglobulin: {
        generic: "Pentaglobulin",
        brand: ["Pentaglobulin"],
        dose: {
            tablet: "0.25gm/kg or 5ml/kg",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: ""
    },
    Nitrazoxanide: {
        generic: "Nitrazoxanide",
        brand: ["Zox Nitazox"],
        dose: {
            tablet: "6 months-1 year: ½ TSF 12 hourly/BD, 1 year-3 years: 1 TSF 12 hourly/BD, 4 years-12 years: 2 TSF 12 hourly/BD",
            syrup: "100mg/5ml",
            drops: "Not available"
        },
        notes: ""
    },
    IVIG: {
        generic: "IVIG",
        brand: ["IVIG"],
        dose: {
            tablet: "1gm/kg for 3 days",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Content: 20ml=1gm, 50ml=2.5gm, 100ml=5gm, 200ml=10gm. Octagam: Only IgG. Indication: 1. Rh Incompatibility, 2. ABO Incompatibility, 3. Neonatal auto-immune thrombocytopenia, 4. ITP (400mg/kg daily for 3-5 days)"
    },
    Clarithromycin: {
        generic: "Clarithromycin",
        brand: ["Klaricid", "Maclar", "Clarox"],
        dose: {
            tablet: "15mg/kg/day",
            syrup: "125mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: BD"
    },
    Chloramphenicol: {
        generic: "Chloramphenicol",
        brand: ["Biophenicol", "Mediphenicol"],
        dose: {
            tablet: "50-100mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Frequency: QDS"
    },
    Domperidone: {
        generic: "Domperidone",
        brand: ["Domin", "Omidon", "Motigut", "Don-A"],
        dose: {
            tablet: "0.4mg/kg/dose",
            syrup: "5mg/5ml",
            drops: "5mg/ml"
        },
        notes: "Duration: QDS/TDS"
    },
    Ondansetron: {
        generic: "Ondansetron",
        brand: ["Emistat", "Anset", "Onaseron", "Ofran"],
        dose: {
            tablet: "0.4mg/kg/dose",
            syrup: "4mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: TDS"
    },
    Diazepam: {
        generic: "Diazepam",
        brand: ["Sedil", "Easium"],
        dose: {
            tablet: "0.5mg/kg/dose (Per Rectal), 0.3mg/kg/dose (IV), 1mg/kg/day (Per oral - not more than 10mg)",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: TDS"
    },
    Phenobarbitone: {
        generic: "Phenobarbitone",
        brand: ["Barbit", "Barbit Elixir", "Epinal"],
        dose: {
            tablet: "20mg/kg Or 10 unit/kg 1st loading dose, 10mg/kg Or 5 unit/kg 2nd loading dose, 10mg/kg Or 5 unit/kg 3rd loading dose, 2.5mg/kg/dose Or 1.5 unit/kg/dose Maintenance dose",
            syrup: "20mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: BD"
    },
    Fosphenytoin: {
        generic: "Fosphenytoin",
        brand: ["Fosphen"],
        dose: {
            tablet: "15-20mg/kg loading dose, 5mg/kg/day Maintenance dose",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: BD"
    },
    Midazolam: {
        generic: "Midazolam",
        brand: ["Dormicum", "Hypnofast"],
        dose: {
            tablet: "0.1-0.2mg/kg Bolus dose then 1-2µg/kg/min or 60-120µg/kg/hour IV fluid",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Amp & Trade: Dormicum (5mg/5ml, 15mg/3ml), Hypnofast (5mg/ml, 5mg/5ml, 15mg/3ml)"
    },
    Clonazepam: {
        generic: "Clonazepam",
        brand: ["Revotril", "Disopan", "Epitra", "Cloron", "Pase"],
        dose: {
            tablet: "Initial dose 100-250µg (not per kg), Maintenance dose 10µg/kg/dose every 8 hour. May be increased to 200µg/kg/day, Infusion dose 10-60µg/kg/hour",
            syrup: "2.5mg/5ml",
            drops: "2.5mg/ml"
        },
        notes: ""
    },
    SodiumValproate: {
        generic: "Sodium Valproate/Valproic Acid",
        brand: ["Valex", "Valpro", "Valopi", "Convules"],
        dose: {
            tablet: "10-30mg/kg/day",
            syrup: "1 TSF= 200mg",
            drops: "Not available"
        },
        notes: "Duration: BD/OD"
    },
    PyrantalPamoet: {
        generic: "Pyrantal Pamoet",
        brand: ["Delentin", "Melphin"],
        dose: {
            tablet: "10mg/kg",
            syrup: "250mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: Single dose, Next after 1 week"
    },
    Mebendazole: {
        generic: "Mebendazole",
        brand: ["Ermox", "Solas", "Meben"],
        dose: {
            tablet: "100mg",
            syrup: "100mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: BD for 3 days"
    },
    Albendazole: {
        generic: "Albendazole",
        brand: ["Alben", "Almex", "Sintel"],
        dose: {
            tablet: "<2 years = 200mg, >2 years = 400mg",
            syrup: "200mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: Single dose, Next after 1 week"
    },
    Paracetamol: {
        generic: "Paracetamol",
        brand: ["Ace", "Napa", "Renova", "Fast", "Reset"],
        dose: {
            tablet: "15mg/kg/dose",
            syrup: "120mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: 4-6h"
    },
    Ranitidine: {
        generic: "Ranitidine",
        brand: ["Ranison", "Neotak", "Ranidine", "Neoceptin-R"],
        dose: {
            tablet: "5mg/kg/day",
            syrup: "75mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: BD"
    },
    Aminophylline: {
        generic: "Aminophylline",
        brand: ["Aminophylline"],
        dose: {
            tablet: "5-6mg/kg Loading dose, 2.5mg/kg/dose Maintenance dose",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: TDS"
    },
    Adrenaline: {
        generic: "Adrenaline",
        brand: ["Adrenaline"],
        dose: {
            tablet: "1ml mixed with 9ml D/W then For NG wash= 1ml/kg, For IV = 0.1ml/kg",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Indication: To stop bleeding, Cardiac arrest."
    },
    Dopamine: {
        generic: "Dopamine",
        brand: ["Dopamine"],
        dose: {
            tablet: "5-20µg/kg/min",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: OD only"
    },
    Digoxin: {
        generic: "Digoxin",
        brand: ["Digoxin"],
        dose: {
            tablet: "Oral dose: 0.04mg/kg, IV Dose: 0.04mg/kg/75%",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Amp: 0.25mg/ml [Each ampule contains 2ml]"
    },
    CalciumGluconate: {
        generic: "Calcium Gluconate",
        brand: ["Calcium Gluconate"],
        dose: {
            tablet: "1-2ml/kg or 10mg/kg/dose",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Dilute with same or double amount of D/W. Duration: Daily"
    },
    SodiumBicarbonate: {
        generic: "Sodium Bicarbonate (NaHCO3)",
        brand: ["Sodium Bicarbonate"],
        dose: {
            tablet: "1-3meq/kg/dose [1meq=1cc]",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Dilute with same or double amount of D/W. Total requirement= 8.3×weight×Base excess in ECF"
    },
    Frusemide: {
        generic: "Frusemide",
        brand: ["Lasix", "Fusid", "Frusin"],
        dose: {
            tablet: "1-2mg/kg/dose",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: ""
    },
    Dexamethasone: {
        generic: "Dexamethasone",
        brand: ["Oradexone", "Roxadex", "Dexa"],
        dose: {
            tablet: "0.2mg/kg/dose",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: TDS/QDS"
    },
    Hydrocortisone: {
        generic: "Hydrocortisone",
        brand: ["Cotson", "Hyson"],
        dose: {
            tablet: "20mg/kg/day or 5mg/kg/dose",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: QDS"
    },
    Prednisolone: {
        generic: "Prednisolone",
        brand: ["Cortan", "Precodil", "Deltasone"],
        dose: {
            tablet: "1-2mg/kg/day",
            syrup: "5mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: OD, BD, TDS"
    },
    FolicAcid: {
        generic: "Folic Acid",
        brand: ["Folison"],
        dose: {
            tablet: "0.5mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: Once daily"
    },
    Mannitol: {
        generic: "Mannitol 20%",
        brand: ["Mannitol"],
        dose: {
            tablet: "5ml/kg",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: OD, TDS"
    },
    Iron: {
        generic: "Iron",
        brand: ["Compiron", "Aneron", "Polyron"],
        dose: {
            tablet: "3-6mg/kg/day",
            syrup: "50mg/5ml",
            drops: "50mg/ml"
        },
        notes: "Duration: OD/BD"
    },
    Chlorohydramine: {
        generic: "Chlorohydramine (Anti histamine)",
        brand: ["Phenadril", "Adryl"],
        dose: {
            tablet: "5mg/kg/TSF",
            syrup: "10mg/ml",
            drops: "Not available"
        },
        notes: "Duration: TDS"
    },
    Kitotifen: {
        generic: "Kitotifen",
        brand: ["Tofen", "Prosma"],
        dose: {
            tablet: "<1 year: ½ TSF, >1 year: ¾ TSF",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: BD"
    },
    Antitussive: {
        generic: "Antitussive (Ambrox)",
        brand: ["Ambrox", "Ambroxol", "Ambolyte", "Ambofen", "Myrox"],
        dose: {
            tablet: "<6 month: 0.5ml/8 drops, >6 month: 1ml/15 drops, 1-2 years: ½ TSF, 2-4 years: ¾ TSF, 5-10 years: 1-2 TSF",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: BD"
    },
    Salbutamol: {
        generic: "Salbutamol",
        brand: ["Sultolin", "Brodil", "Pulmolin", "Salmolin", "Ventolin", "Purisal"],
        dose: {
            tablet: "Syrup: 0.2-0.4mg/kg/day 8 hourly, Nebulization: 0.04ml/kg/dose 4-6 hourly, Respiratory solution: 0.15-0.3mg/kg/dose 6 hourly",
            syrup: "2mg/5ml",
            drops: "Not available"
        },
        notes: ""
    },
    IpratropiumBromide: {
        generic: "Ipratropium Bromide",
        brand: ["Iprex"],
        dose: {
            tablet: "<6 month: 0.25ml/dose, >6 month: 0.5ml/dose, ≤2 years: 125-250µg (1ml)/dose, ≥2 years: 250-500µg (1-2ml)/dose",
            syrup: "250µg/ml (20ml Bottle)",
            drops: "Not available"
        },
        notes: "Duration: 4-6 hourly"
    },
    BudesonideNebulization: {
        generic: "Budesonide nebulization",
        brand: ["Budicort"],
        dose: {
            tablet: "Child: 0.5-1mg initial dose then 0.25-0.5mg maintenance dose, Adult: 1-2mg",
            syrup: "1mg/2ml (Nebulization Solution)",
            drops: "Not available"
        },
        notes: "Duration: 4-6 hourly"
    },
    Ibuprofen: {
        generic: "Ibuprofen",
        brand: ["Esrufen", "Flamex", "Inflam", "Profen", "Reumafen"],
        dose: {
            tablet: "10mg/kg/day",
            syrup: "100mg/5ml",
            drops: "Not available"
        },
        notes: "Duration: BD"
    },
    InfantOfHBsAgPositiveMother: {
        generic: "Infant of HBsAg (+ve) mother",
        brand: ["Inj. Engerix B", "Inj. Hepabig"],
        dose: {
            tablet: "Total 3 doses=0,1st,2nd month, Adult 3 doses= 0, 1st, 6th month",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Within 24 hours or as early as possible. Procedure: 1 amp I/M in one thigh, 1 amp I/M in another thigh"
    },
    NaStibogluconate: {
        generic: "Na Stibogluconate",
        brand: ["Stibanate", "Stibatin"],
        dose: {
            tablet: "20mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: OD. Amp: 30ml [1ml=100mg]. IV slowly over 1 hour after dilute with N/S (50ml)"
    },
    Nifedipine: {
        generic: "Nifedipine",
        brand: ["Nifin", "Nificap gel"],
        dose: {
            tablet: "0.25-0.5mg/kg/day",
            syrup: "Not available",
            drops: "8 drops=10mg [1 drop=1.25mg]"
        },
        notes: "Duration: BD/TDS"
    },
    DiclofenacNa: {
        generic: "Diclofenac Na",
        brand: ["Voltaline", "Clofenac", "Diclofen"],
        dose: {
            tablet: "1-3mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: BD"
    },
    Prostaglandin: {
        generic: "Prostaglandin",
        brand: ["Prostaglandin"],
        dose: {
            tablet: "0.1µg/kg/min",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Dilute with 1ml (500µg) in 82ml N/S. Then give 1ml/kg/hour which is equal to 0.1ml/kg/min"
    },
    BloodFFP: {
        generic: "Blood/FFP",
        brand: ["Blood/FFP"],
        dose: {
            tablet: "10-20ml/kg",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: ""
    },
    ACEInhibitor: {
        generic: "ACE inhibitor",
        brand: ["Captopril"],
        dose: {
            tablet: "0.25-6mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: ""
    },
    KTSyrup: {
        generic: "KT Syrup",
        brand: ["KT Syrup"],
        dose: {
            tablet: "1-2mmol/kg/day [1TSF=6.5mmol/L]",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: TDS"
    },
    Enalapril: {
        generic: "Enalapril",
        brand: ["Enaril"],
        dose: {
            tablet: "0.2-0.3mg/kg/day",
            syrup: "Not available",
            drops: "Not available"
        },
        notes: "Duration: BD"
    }
};

function showSuggestions() {
    const search = document.getElementById('drugSearch').value.toLowerCase();
    const suggestions = document.getElementById('suggestions');
    suggestions.innerHTML = '';

    for (const key in drugData) {
        if (drugData[key].generic.toLowerCase().includes(search) || drugData[key].brand.some(b => b.toLowerCase().includes(search))) {
            const suggestion = document.createElement('a');
            suggestion.href = '#';
            suggestion.className = 'list-group-item list-group-item-action';
            suggestion.textContent = drugData[key].generic;
            suggestion.onclick = function() {
                document.getElementById('drugSearch').value = drugData[key].generic;
                showSuggestions();
            };
            suggestions.appendChild(suggestion);
        }
    }
}

function calculateDose() {
    const search = document.getElementById('drugSearch').value.toLowerCase();
    const weight = parseFloat(document.getElementById('weight').value);
    const doseForm = document.getElementById('doseForm').value;
    const resultDiv = document.getElementById('result');

    if (isNaN(weight) || weight <= 0) {
        resultDiv.innerHTML = "<div class='alert alert-danger'>Please enter a valid weight.</div>";
        return;
    }

    let drug = null;
    for (const key in drugData) {
        if (drugData[key].generic.toLowerCase() === search || drugData[key].brand.some(b => b.toLowerCase() === search)) {
            drug = drugData[key];
            break;
        }
    }

    if (!drug) {
        resultDiv.innerHTML = "<div class='alert alert-danger'>Drug not found.</div>";
        return;
    }

    let dose = drug.dose[doseForm];
    let result = "";
    if (doseForm === "tablet") {
        const mgPerKg = parseFloat(dose.split("mg/kg")[0]);
        const totalMg = mgPerKg * weight;
        result = `Dose: ${totalMg.toFixed(2)} mg`;
    } else if (doseForm === "syrup") {
        const mgPerMl = parseFloat(dose.split("mg/")[0]);
        const totalMg = (mgPerMl / 5) * weight; // Calculate total mg needed
        const totalMl = totalMg / mgPerMl; // Calculate total ml needed
        const tsf = totalMl / 5; // Convert ml to teaspoonfuls
        result = `Dose: ${totalMg.toFixed(2)} mg, ${totalMl.toFixed(2)} ml (${tsf.toFixed(2)} TSF)`;
    } else if (doseForm === "drops") {
        const mgPerDrop = parseFloat(dose.split("mg/")[0]);
        const totalDrops = (weight * mgPerDrop) / 100;
        result = `Dose: ${totalDrops.toFixed(2)} drops`;
    } else if (doseForm === "suppository") {
        const mg = parseFloat(dose.split("mg")[0]);
        result = `Dose: ${mg} mg`;
    } else if (doseForm === "inhaler") {
        const mg = parseFloat(dose.split("mg")[0]);
        result = `Dose: ${mg} mg`;
    }

    resultDiv.innerHTML = `<div class='alert alert-success'><strong>${drug.generic}</strong><br>${result}<br><small>${drug.notes}</small></div>`;
}
