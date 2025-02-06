export const drugDatabase = {
  ampicillin: {
    name: 'Ampicillin',
    tradeNames: ['Ampexin', 'Acmecillin', 'Ficillin', 'Pen-A'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['250mg/2.5ml', '500mg/5ml'] },
      capsule: { strength: ['250mg', '500mg'] },
      syrup: { strength: '125mg/5ml' },
      drops: { strength: '125mg/1.25ml' }
    },
    dosage: {
      standard: '100mg/kg/day',
      neonate: '12 hourly',
      infantAndOlder: '8 hourly'
    }
  },
  gentamycin: {
    name: 'Gentamycin',
    tradeNames: ['Gentin', 'Genacyn', 'Invigen'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['20mg/2ml', '80mg/2ml'] }
    },
    dosage: {
      standard: '5mg/kg/day or 0.5ml/kg/dose',
      neonate: 'Once daily',
      infantAndOlder: '8 hourly'
    }
  },
  amikacin: {
    name: 'Amikacin',
    tradeNames: ['Kacin', 'Amibac', 'Amistar'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['100mg/2ml', '500mg/2ml'] }
    },
    dosage: {
      standard: '7.5mg/kg/day or 15mg/kg/day or 0.15ml/kg/dose',
      neonate: '12 hourly',
      infantAndOlder: '8 hourly'
    },
    notes: 'Caution: Renal impairment.'
  },
  ceftazidime: {
    name: 'Ceftazidime',
    tradeNames: ['Tazid', 'Zitum', 'Serozid', 'Sidobac', 'Trum-3', 'Cefazid'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['250mg/2.5ml', '500mg/5ml', '1gm/10ml'] }
    },
    dosage: {
      standard: '100mg/kg/day or 0.5ml/kg/dose',
      meningitis: '150mg/kg/day',
      neonate: '12 hourly',
      infantAndOlder: '8 hourly'
    }
  },
  cefotaxime: {
    name: 'Cefotaxime',
    tradeNames: ['Maxcef', 'Cefotime', 'Taxim', 'Cefotex'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['250mg/2.5ml', '500mg/5ml', '1gm/10ml'] }
    },
    dosage: {
      standard: '100mg/kg/day',
      meningitis: '150mg/kg/day',
      duration: '8 hourly'
    }
  },
  ceftriaxone: {
    name: 'Ceftriaxone',
    tradeNames: ['Ceftron', 'Dicephin', 'Roficin', 'Oricef', 'Arixon', 'Axon', 'Traxef', 'Ceftizone'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['250mg/2.5ml', '500mg/5ml', '1gm/10ml', '2gm/20ml'] }
    },
    dosage: {
      standard: '50-100mg/kg OD/BD',
      meningitis: '100mg/kg OD/BD',
      entericFever: '75mg/kg OD/BD',
      therapeuticRange: 'Up to 2gm BD'
    }
  },
  imipenem: {
    name: 'Imipenem',
    tradeNames: ['Imenem', 'Cispenam'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: '500mg/100ml' }
    },
    dosage: {
      standard: '60mg/kg/day or 20mg/kg/dose or 4ml/kg/dose',
      duration: 'TDS'
    },
    notes: 'Always given after dilution with 10ml of IV fluid.'
  },
  meropenem: {
    name: 'Meropenem',
    tradeNames: ['Spacbac', 'I-Penem', 'Fulspec', 'Ropenam', 'Neopenam', 'Merocon', 'Meropen'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: '500mg/10ml' }
    },
    dosage: {
      standard: '60mg/kg/day or 20mg/kg/dose or 0.4ml/kg/dose',
      duration: 'TDS'
    },
    notes: 'Always given after dilution with 10ml of IV fluid.'
  },
  vancomycin: {
    name: 'Vancomycin',
    tradeNames: ['Vancomycin', 'Vanmycin', 'Vancomin', 'Covan'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: '500mg/100ml' }
    },
    dosage: {
      lessThan7Days: '15mg/kg/dose 12 hourly/BD',
      moreThan7Days: '15mg/kg/dose 8 hourly/TDS'
    },
    notes: 'Always given after dilution with 10ml of IV fluid.'
  },
  flucloxacillin: {
    name: 'Flucloxacillin',
    tradeNames: ['Fluclox', 'Phylopen', 'Phylopen Forte DS', 'Flux', 'Flubex', 'Flupen', 'Flubac'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['250mg/2.5ml', '500mg/5ml'] },
      capsule: { strength: ['250mg', '500mg'] },
      syrup: { strength: ['125mg/5ml', '250mg/5ml'] }
    },
    dosage: {
      injection: '25mg/kg/dose Or 100mg/kg/day',
      oral: '50mg/kg/day',
      duration: 'QDS'
    },
    notes: '1 ml = 15 drops, 1 TSF = 5 ml'
  },
  cephradine: {
    name: 'Cephradine',
    tradeNames: ['Dicef', 'Cephran'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['250mg', '500mg'] },
      capsule: { strength: ['250mg', '500mg'] },
      syrup: { strength: '125mg/5ml' }
    },
    dosage: {
      standard: '50mg/kg/day',
      duration: 'QDS'
    }
  },
  piperacillinTazobactam: {
    name: 'Piperacillin + Tazobactam',
    tradeNames: ['Megacillin', 'Tazopen'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: '4.5gm/20ml' }
    },
    dosage: {
      standard: '50-100mg/kg/dose TDS',
      duration: '8 hourly'
    }
  },
  amoxicillin: {
    name: 'Amoxicillin',
    tradeNames: ['Moxacil', 'Moxin', 'Tycil', 'Fimoxyl'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['250mg/2.5ml', '500mg/5ml'] },
      capsule: { strength: ['250mg', '500mg'] },
      syrup: { strength: '125mg/5ml' },
      drops: { strength: '125mg/1.25ml' }
    },
    dosage: {
      standard: '40mg/kg/day',
      duration: 'TDS'
    }
  },
  amoxicillinClavulanicAcid: {
    name: 'Amoxicillin + Clavulanic Acid',
    tradeNames: ['Moxaclav', 'Fimoxyclav', 'Ticlav'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['600mg', '1.2gm'] },
      capsule: { strength: ['375mg', '625mg', '1gm'] },
      syrup: { strength: '156mg/5ml' }
    },
    dosage: {
      standard: '40mg/kg/day',
      neonate: '30mg/kg/day',
      duration: 'TDS (Neonate: BD)'
    }
  },
  cefuroxime: {
    name: 'Cefuroxime',
    tradeNames: ['Cefotil', 'Kilbac', 'Sefur', 'Cefobac', 'Rofurox', 'Sefurox', 'Cerox-A', 'Furocef'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['250mg', '500mg', '750mg', '1.5gm'] },
      capsule: { strength: ['125mg', '250mg', '500mg'] },
      syrup: { strength: '125mg/5ml' }
    },
    dosage: {
      parenteral: '20mg/kg/day',
      oral: '30mg/kg/day',
      duration: '12 hourly'
    }
  },
  ciprofloxacin: {
    name: 'Ciprofloxacin',
    tradeNames: ['Ciprocin', 'Neofloxin', 'Flontin', 'Beuflox', 'Ciprox', 'Cipro-A', 'X-bac'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: '200mg/100ml' },
      capsule: { strength: ['250mg', '500mg', '750mg'] },
      syrup: { strength: '250mg/5ml' }
    },
    dosage: {
      standard: '15-30mg/Kg/day or 5ml/kg/dose',
      duration: 'BD'
    }
  },
  azithromycin: {
    name: 'Azithromycin',
    tradeNames: ['Zimax'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: '500mg/100ml' },
      capsule: { strength: ['250mg', '500mg'] },
      syrup: { strength: '200mg/5ml' }
    },
    dosage: {
      standard: '10mg/kg/day (5-10mg/kg/day)',
      entericFever: '20mg/kg/day',
      duration: 'OD/BD'
    }
  },
  cefixime: {
    name: 'Cefixime',
    tradeNames: ['Cef-3', 'Denvar', 'T-Cef', 'Ceftid', 'Rofixim', 'Emixef', 'Roxim', 'Truso', 'Triocim', 'Orcef', 'Starcef'],
    category: 'Antibiotics',
    forms: {
      capsule: { strength: ['200mg', '400mg'] },
      syrup: { strength: '100mg/5ml' }
    },
    dosage: {
      standard: '10-20mg/kg/day',
      duration: 'BD'
    }
  },
  cefepime: {
    name: 'Cefepime',
    tradeNames: ['Ceftipime', 'Maxipime', 'Xenim', 'Ultrapime'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ['500mg', '1gm', '2gm'] }
    },
    dosage: {
      lessThan14Days: '30mg/kg/day 12 hourly',
      moreThan14Days: '50mg/kg/day 12 hourly/8 hourly'
    }
  },
  cephalexin: {
    name: 'Cephalexin',
    tradeNames: ['Ceftipime', 'Cephalen', 'Ceporin', 'Neorex'],
    category: 'Antibiotics',
    forms: {
      capsule: { strength: ['250mg', '500mg'] },
      syrup: { strength: '125mg/5ml' },
      drops: { strength: '125mg/1.25ml' }
    },
    dosage: {
      lessThan7Days: '25mg/kg/dose 12 hourly',
      moreThan7Days: '25mg/kg/dose 8 hourly'
    }
  },
  cefpodoxime: {
    name: 'Cefpodoxime',
    tradeNames: ['Vanprox', 'Ximprox', 'Vercef', 'Kidcef', 'Roxitil', 'Trucef'],
    category: 'Antibiotics',
    forms: {
      syrup: { strength: '40mg/5ml' },
      drops: { strength: '20mg/ml' }
    },
    dosage: {
      standard: '10mg/kg/day',
      duration: 'BD'
    },
    notes: '1 ml = 15 drops'
  },
  netilmicin: {
    name: 'Netilmicin',
    tradeNames: ['Netromycin'],
    category: 'Antibiotics',
    forms: {
      ampule: { 
        strength: {
          'Infant-2year': '50mg/2ml',
          '>2year': '100mg/1ml'
        }
      }
    },
    dosage: {
      firstWeek: '6mg/kg/day 12 hourly over 30 min inf. Or IV 8 hourly',
      later: '7.5-9mg/kg/day or 2.5-3mg/kg/dose 8 Hourly'
    }
  },
  colistin: {
    name: 'Colistin',
    tradeNames: ['Colomycin'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: '1million/10ml' }
    },
    dosage: {
      standard: '25000unit/kg/dose or 0.25ml/kg/dose',
      duration: '8 hourly'
    },
    notes: 'Inj. Colomycin (1 million) after dilute with 10ml D/W\n10ml contain = 1 million unit\n      1ml contain = 1 lakh unit\n      So\nif wt\n2kg - 2*25000 = 50000 unit = 0.5ml (So, 0.5ml + 10ml IV slowly)'
  },
  clindamycin: {
    name: "Clindamycin",
    tradeNames: ['Clindex', 'Clindacin', 'Climycin'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ["300mg/2ml", "600mg/4ml"] },
      capsule: { strength: ["150mg", "300mg"] },
      syrup: { strength: "75mg/5ml" }
    },
    dosage: {
      "0-14days": '3-6mg/kg/dose 8 hourly/TDS',
      '15days-12yrs': '3-6mg/kg/dose 6 hourly/QDS'
    }
  },
  erythromycin: {
    name: "Erythromycin",
    tradeNames: ['Eromycin', 'A-mycin', 'Erythrox'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ["250mg", "500mg"] },
      syrup: { strength: "125mg/5ml" }
    },
    dosage: {
      standard: "50mg/kg/day",
      duration: 'QDS'
    }
  },
  metronidazole: {
    name: "Metronidazole",
    tradeNames: ['Amodis', 'Flamyd', 'Flazyl', 'Metro', 'Menz', 'Metryl', 'Filmet'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: "500mg/100ml" },
      capsule: { strength: ["400mg", "500mg"] },
      syrup: { strength: "200mg/5ml" }
    },
    dosage: {
      standard: "20-30mg/kg/day or 7.5-10mg/kg/dose or 1.5ml/kg/dose",
      duration: 'TDS'
    }
  },
  acyclovir: {
    name: "Acyclovir",
    tradeNames: ['Zovirux', 'Xovir', 'Virux', 'Novirux'],
    category: 'Antivirals',
    forms: {
      syrup: { strength: "200mg/5ml" },
      vial: { strength: ["250mg", "500mg"] }
    },
    dosage: {
      herpesEncephalitisVaricella: "10mg/kg/dose",
      herpesSimplex: '5mg/kg/dose',
      duration: 'TDS'
    },
    notes: '1st dilute with 10ml D/W, then again dilute with 10-20ml I/V fluid over 60 mins'
  },
  ganciclovir: {
    name: "Ganciclovir",
    tradeNames: ['Cymevene', 'Cytovene'],
    category: 'Antivirals',
    forms: {
      vial: { strength: "500mg/10ml" }
    },
    dosage: {
      standard: "6mg/kg/dose",
      duration: '12 hourly'
    },
    notes: 'I/V fluid over 60 mins'
  },
  fluconazole: {
    name: "Fluconazole",
    tradeNames: ['Flugal', 'Lucan-R', 'Nispore', 'Omastin', 'Candinil', 'Conaz', 'Flucess', 'Flucoder', 'Flucon'],
    category: 'Antifungals',
    forms: {
      vial: { strength: ["50mg", "150mg"] },
      syrup: { strength: "50mg/5ml" }
    },
    dosage: {
      loadingDose: "12mg/kg/dose",
      maintenance: {
        "≤14days": '6mg/kg/dose Once every 3 days',
        '15-27days': 'Once every 2 days',
        '≥28days': 'Once daily'
      },
      prophylaxis: '3mg/kg/dose Once every 3 days'
    }
  },
  nystatin: {
    name: "Nystatin",
    tradeNames: ['Nystat', 'Candex', 'Fungistin', 'Naf'],
    category: 'Antifungals',
    forms: {
      vial: { strength: "500,000 unit" },
      drops: { strength: "100,000 unit/ml" }
    },
    dosage: {
      standard: "1ml/15drops",
      duration: 'TDS/QDS'
    }
  },
  chloroquine: {
    name: "Chloroquine",
    tradeNames: ['Avloquin', 'Jesochlor'],
    category: 'Antimalarials',
    forms: {
      tablet: { strength: "250mg" },
      syrup: { strength: "80mg/5ml" }
    },
    dosage: {
      day1: "10mg/kg",
      day2: '5mg/kg',
      day3: '5mg/kg'
    }
  },
  primaquine: {
    name: "Primaquine",
    category: 'Antimalarials',
    forms: {
      tablet: { strength: "15mg" }
    },
    dosage: {
      standard: "0.9mg/kg",
      duration: 'Single Dose'
    },
    notes: '4 tab on day 4'
  },
  cotrimoxazole: {
    name: "Cotrimoxazole (Sulphamethoxazole + Trimethoprim)",
    tradeNames: ['Cotrim', 'Bactipront'],
    category: 'Antibiotics',
    forms: {
      tablet: { strength: ["480mg (400S+80T)", "960mg (800S+160T)"] },
      syrup: { strength: "240mg/5ml (200S+40T)" }
    },
    dosage: {
      standard: "8mg/kg/day",
      duration: 'BD'
    }
  },
  quinine: {
    name: "Quinine",
    tradeNames: ['Jasoquin'],
    category: 'Antimalarials',
    forms: {
      vial: { strength: "60mg/ml" },
      tablet: { strength: "Not specified" }
    },
    dosage: {
      loading: "20mg/kg for 4 hours & after 8 hours of Loading",
      maintenance: '10mg/kg/day in TDS',
      oral: '10mg/kg/day TDS on D1, D2, D3'
    },
    notes: 'On D3 Quinine + Tab. Fancider/Malacide 525mg single dose [Sulphadoxin 500mg (25mg/kg) & Pyrimethamine 25mg (1.25mg/kg)]'
  },
  pentaglobulin: {
    name: "Pentaglobulin",
    category: 'Immunoglobulins',
    forms: {
      vial: { strength: "0.5gm/10ml" },
      ampule: { strength: ["10ml", "50ml", "100ml"] }
    },
    dosage: {
      standard: "0.25gm/kg or 5ml/kg"
    }
  },
  nitazoxanide: {
    name: "Nitazoxanide",
    tradeNames: ['Zox', 'Nitazox'],
    category: 'Antiparasitics',
    forms: {
      syrup: { strength: "100mg/5ml" },
      tablet: { strength: "500mg" }
    },
    dosage: {
      "6months-1year": '½ TSF 12 hourly/BD',
      '1year-3years': '1 TSF 12 hourly/BD',
      '4years-12years': '2 TSF 12 hourly/BD'
    }
  },
  ivig: {
    name: "IVIG",
    category: 'Immunoglobulins',
    forms: {
      vial: { strength: ["20ml=1gm", "50ml=2.5gm", "100ml=5gm", "200ml=10gm"] }
    },
    dosage: {
      standard: "1gm/kg for 3 days"
    },
    notes: 'Octagam: Only IgG. Indications: 1. Rh Incompatibility, 2. ABO Incompatibility, 3. Neonatal auto-immune thrombocytopenia, 4. ITP (400mg/kg daily for 3-5 days)'
  },
  clarithromycin: {
    name: "Clarithromycin",
    tradeNames: ['Klaricid', 'Maclar', 'Clarox'],
    category: 'Antibiotics',
    forms: {
      syrup: { strength: "125mg/5ml" },
      ampule: { strength: "500mg/10ml" }
    },
    dosage: {
      standard: "15mg/kg/day or 0.15ml/kg/dose",
      duration: 'BD'
    }
  },
  chloramphenicol: {
    name: "Chloramphenicol",
    tradeNames: ['Biophenicol', 'Mediphenicol'],
    category: 'Antibiotics',
    forms: {
      vial: { strength: ["500mg", "1gm"] }
    },
    dosage: {
      standard: "50-100mg/kg/day",
      frequency: 'QDS'
    }
  },
  domperidone: {
    name: "Domperidone",
    tradeNames: ['Domin', 'Omidon', 'Motigut', 'Don-A'],
    category: 'Antiemetics',
    forms: {
      tablet: { strength: "10mg" },
      syrup: { strength: "5mg/5ml" },
      drops: { strength: "5mg/ml" },
      suppository: { strength: ["15mg", "30mg"] }
    },
    dosage: {
      standard: "0.4mg/kg/dose",
      duration: 'QDS/TDS'
    }
  },
  ondansetron: {
    name: "Ondansetron",
    tradeNames: ['Emistat', 'Anset', 'Onaseron', 'Ofran'],
    category: 'Antiemetics',
    forms: {
      vial: { strength: "8mg/4ml" },
      tablet: { strength: ["4mg", "8mg"] },
      syrup: { strength: "4mg/5ml" },
      suppository: { strength: ["8mg", "16mg"] }
    },
    dosage: {
      standard: "0.4mg/kg/dose",
      duration: 'TDS'
    }
  },
  diazepam: {
    name: "Diazepam",
    tradeNames: ['Sedil', 'Easium'],
    category: 'Anticonvulsants',
    forms: {
      vial: { strength: "10mg/2ml" },
      tablet: { strength: "5mg" }
    },
    dosage: {
      perRectal: "0.5mg/kg/dose",
      iv: '0.3mg/kg/dose',
      perOral: '1mg/kg/day (not more than 10mg)',
      duration: 'TDS'
    }
  },
  phenobarbital: {
    name: "Phenobarbital",
    tradeNames: ['Barbit', 'Barbit Elixir', 'Epinal'],
    category: 'Anticonvulsants',
    forms: {
      vial: { strength: "200mg/ml" },
      syrup: { strength: "20mg/5ml" },
      tablet: { strength: ["30mg", "60mg"] }
    },
    dosage: {
      firstLoading: "20mg/kg Or 10unit/kg",
      secondLoading: '10mg/kg Or 5unit/kg',
      thirdLoading: '10mg/kg Or 5unit/kg',
      maintenance: '2.5mg/kg/dose Or 1.5unit/kg/dose',
      duration: 'BD'
    },
    notes: "Don't dilute because it's oily"
  },
  fosphenytoin: {
    name: "Fosphenytoin",
    tradeNames: ['Fosphen'],
    category: 'Anticonvulsants',
    forms: {
      vial: { strength: "150mg/2ml" }
    },
    dosage: {
      loading: "15-20mg/kg",
      maintenance: '5mg/kg/day',
      duration: 'BD'
    }
  },
  midazolam: {
    name: "Midazolam",
    tradeNames: ['Dormicum', 'Hypnofast'],
    category: 'Anticonvulsants',
    forms: {
      vial: { strength: ["5mg/5ml", "15mg/3ml"] }
    },
    dosage: {
      bolus: "0.1-0.2mg/kg",
      maintenance: '1-2μg/kg/min or 60-120μg/kg/hour IV fluid'
    },
    notes: '1000μg = 1mg'
  },
  clonazepam: {
    name: "Clonazepam",
    tradeNames: ['Revotril', 'Disopan', 'Epitra', 'Cloron', 'Pase'],
    category: 'Anticonvulsants',
    forms: {
      syrup: { strength: "2.5mg/5ml" },
      drops: { strength: "2.5mg/ml" },
      tablet: { strength: ["0.5mg", "2mg"] }
    },
    dosage: {
      initial: "100-250μg (not per kg)",
      maintenance: '10μg/kg/dose every 8 hour. May be increased to 200μg/kg/day',
      infusion: '10-60μg/kg/hour'
    }
  },
  sodiumValproate: {
    name: "Sodium Valproate/Valproic Acid",
    tradeNames: ['Valex', 'Valpro', 'Valopi', 'Convules'],
    category: 'Anticonvulsants',
    forms: {
      syrup: { strength: "200mg/5ml" },
      tablet: { strength: "200mg" }
    },
    dosage: {
      standard: "10-30mg/kg/day",
      duration: 'BD/OD'
    }
  },
  pyrantelPamoate: {
    name: "Pyrantel Pamoate",
    tradeNames: ['Delentin', 'Melphin'],
    category: 'Antihelminthics',
    forms: {
      syrup: { strength: "250mg/5ml" }
    },
    dosage: {
      standard: "10mg/kg",
      duration: 'Single dose, Next after 1 week'
    }
  },
  mebendazole: {
    name: "Mebendazole",
    tradeNames: ['Ermox', 'Solas', 'Meben'],
    category: 'Antihelminthics',
    forms: {
      syrup: { strength: "100mg/5ml" },
      tablet: { strength: "100mg" }
    },
    dosage: {
      standard: "100mg",
      duration: 'BD for 3 days'
    },
    notes: 'USE AFTER 1 YEAR'
  },
  albendazole: {
    name: "Albendazole",
    tradeNames: ['Alben', 'Almex', 'Sintel'],
    category: 'Antihelminthics',
    forms: {
      syrup: { strength: "200mg/5ml" },
      tablet: { strength: "400mg" }
    },
    dosage: {
      lessThan2Years: "200mg",
      moreThan2Years: '400mg',
      duration: 'Single dose, Next after 1 Week same time.'
    }
  },
  paracetamol: {
    name: "Paracetamol",
    tradeNames: ['Ace', 'Napa', 'Renova', 'Fast', 'Reset'],
    category: 'Others',
    forms: {
      tablet: { strength: ["250mg", "500mg", "665mg"] },
      syrup: { strength: "120mg/5ml" },
      drops: { strength: "80mg/ml" },
      suppository: { strength: ["60mg", "125mg", "250mg", "500mg"] }
    },
    dosage: {
      standard: "15mg/kg/dose",
      duration: '4-6h'
    },
    notes: '1ml = 80mg = 15 drops, 1 TSF = 5ml'
  },
  ranitidine: {
    name: "Ranitidine",
    tradeNames: ['Ranison', 'Neotak', 'Ranidine', 'Neoceptin-R'],
    category: 'Others',
    forms: {
      vial: { strength: "50mg/2ml" },
      capsule: { strength: "150mg" },
      syrup: { strength: "75mg/5ml" }
    },
    dosage: {
      standard: "5mg/kg/day",
      duration: 'BD'
    }
  },
  aminophylline: {
    name: "Aminophylline",
    category: 'Others',
    forms: {
      syrup: { strength: "125mg/5ml" }
    },
    dosage: {
      loading: "5-6mg/kg",
      maintenance: '2.5mg/kg/dose',
      duration: 'TDS'
    }
  },
  adrenaline: {
    name: 'Adrenaline',
    category: 'Others',
    forms: {
      ampuleadrenaline: {
    name: 'Adrenaline',
    category: 'Others',
    forms: {
      ampule: { strength: 'Not specified' }
    },
    dosage: {
      ngWash: '1ml/kg',
      iv: '0.1ml/kg'
    },
    notes: '1ml mixed with 9ml D/W then use. Indication: To stop bleeding, Cardiac arrest.'
  },
  dopamine: {
    name: 'Dopamine',
    category: 'Others',
    forms: {
      strength: '200mg/5ml'
    },
    dosage: {
      standard: '5-20μg/kg/min',
      duration: 'OD only'
    },
    notes: 'Should be given in each 100ml of fluid'
  },
  digoxin: {
    name: 'Digoxin',
    category: 'Others',
    forms: {
      strength: '0.25mg/ml'
    },
    dosage: {
      oral: '0.04mg/kg',
      iv: '75% of oral dose'
    },
    notes: 'Each ampule contains 2ml. First give result\'s half dose – stat, Then give 1/4th dose - 8 hour later, After that give another 1/4th - 8 hour later'
  },
  calciumGluconate: {
    name: 'Calcium Gluconate',
    category: 'Others',
    forms: {
      strength: '9mg/ml'
    },
    dosage: {
      standard: '1-2ml/kg or 10mg/kg/dose',
      duration: 'Daily'
    },
    notes: 'Dilute with same or double amount of D/W'
  },
  sodiumBicarbonate: {
    name: 'Sodium Bicarbonate (NaHCO3)',
    category: 'Others',
    forms: {
      strength: 'Not specified'
    },
    dosage: {
      standard: '1-3meq/kg/dose'
    },
    notes: '1meq = 1cc. Dilute with same or double amount of D/W. Total requirement = 8.3 × weight × Base excess in ECF. First give result\'s half dose - stat, Then give another half dose - 8 hours later.'
  },
  furosemide: {
    name: 'Furosemide',
    tradeNames: ['Lasix', 'Fusid', 'Frusin'],
    category: 'Others',
    forms: {
      vial: { strength: '20mg/2ml' },
      tablet: { strength: '40mg' }
    },
    dosage: {
      standard: '1-2mg/kg/dose'
    }
  },
  dexamethasone: {
    name: 'Dexamethasone',
    tradeNames: ['Oradexone', 'Roxadex', 'Dexa'],
    category: 'Others',
    forms: {
      strength: '5mg/ml'
    },
    dosage: {
      standard: '0.2mg/kg/dose',
      duration: 'TDS/QDS'
    }
  },
  hydrocortisone: {
    name: 'Hydrocortisone',
    tradeNames: ['Cotson', 'Hyson'],
    category: 'Others',
    forms: {
      strength: '100mg/2ml'
    },
    dosage: {
      standard: '20mg/kg/day or 5mg/kg/dose',
      duration: 'QDS'
    }
  },
  prednisolone: {
    name: 'Prednisolone',
    tradeNames: ['Cortan', 'Precodil', 'Deltasone'],
    category: 'Others',
    forms: {
      tablet: { strength: ['5mg', '10mg', '20mg'] },
      syrup: { strength: '5mg/5ml' }
    },
    dosage: {
      standard: '1-2mg/Kg/day',
      duration: 'OD, BD, TDS'
    }
  },
  folicAcid: {
    name: 'Folic Acid',
    tradeNames: ['Folison'],
    category: 'Others',
    forms: {
      tablet: { strength: '5mg' }
    },
    dosage: {
      standard: '0.5mg/kg/day once daily'
    }
  },
  mannitol: {
    name: 'Mannitol 20%',
    category: 'Others',
    forms: {
      strength: '20%'
    },
    dosage: {
      standard: '5ml/kg',
      duration: 'OD, TDS'
    }
  },
  iron: {
    name: 'Iron',
    tradeNames: ['Compiron', 'Aneron', 'Polyron'],
    category: 'Others',
    forms: {
      syrup: { strength: '50mg/5ml' },
      drops: { strength: '50mg/ml' }
    },
    dosage: {
      standard: '3-6mg/kg/day',
      duration: 'OD/BD'
    }
  },
  chlorpheniramine: {
    name: 'Chlorpheniramine (Antihistamine)',
    tradeNames: ['Phenadril', 'Adryl'],
    category: 'Others',
    forms: {
      strength: '10mg/ml'
    },
    dosage: {
      standard: '5mg/kg/TSF',
      duration: 'TDS'
    }
  },
  ketotifen: {
    name: 'Ketotifen',
    tradeNames: ['Tofen', 'Prosma'],
    category: 'Others',
    forms: {
      strength: 'Not specified'
    },
    dosage: {
      lessThan1Year: '½ TSF',
      moreThan1Year: '¾ TSF',
      duration: 'BD'
    }
  },
  ambroxol: {
    name: 'Ambroxol (Antitussive)',
    tradeNames: ['Ambrox', 'Ambroxol', 'Ambolyte', 'Ambofen', 'Myrox'],
    category: 'Others',
    forms: {
      strength: 'Not specified',
      drops: { strength: 'Not specified' }
    },
    dosage: {
      lessThan6Month: '0.5ml/8drops',
      moreThan6Month: '1ml/15drops',
      '1-2years': '½ TSF',
      '2-4years': '¾ TSF',
      '5-10years': '1-2 TSF',
      duration: 'BD'
    }
  },
  salbutamol: {
    name: 'Salbutamol',
    tradeNames: ['Sultolin', 'Brodil', 'Pulmolin', 'Salmolin', 'Ventolin', 'Purisal'],
    category: 'Others',
    forms: {
      tablet: { strength: ['2mg', '4mg'] },
      syrup: { strength: '2mg/5ml' },
      respiratorySolution: { strength: '5mg/ml' }
    },
    dosage: {
      syrup: '0.2-0.4mg/kg/day 8 hourly',
      nebulization: '0.04ml/kg/dose 4-6 hourly',
      respiratorySolution: '0.15-0.3mg/kg/dose 6 hourly'
    }
  },
  ipratropiumBromide: {
    name: 'Ipratropium Bromide',
    tradeNames: ['Iprex'],
    category: 'Others',
    forms: {
      vial: { strength: '250μg/ml (20ml Bottle)' }
    },
    dosage: {
      lessThan6Month: '0.25ml/dose',
      moreThan6Month: '0.5ml/dose',
      lessThanEqualTo2Years: '125-250μg(1ml)/dose',
      moreThanEqualTo2Years: '250-500μg(1-2ml)/dose',
      duration: '4-6 hourly'
    }
  },
  salbutamolIpratropiumBromide: {
    name: 'Salbutamol + Ipratropium Bromide',
    tradeNames: ['Sulprex', 'Windal Solution'],
    category: 'Others',
    forms: {
      strength: 'Not specified'
    }
  },
  budesonide: {
    name: 'Budesonide nebulization',
    tradeNames: ['Budicort'],
    category: 'Others',
    forms: {
      strength: '1mg/2ml (Nebulization Solution)'
    },
    dosage: {
      child: '0.5-1mg initial dose then 0.25-0.5mg maintenance dose',
      adult: '1-2mg',
      duration: '4-6 hourly'
    }
  },
  ibuprofen: {
    name: 'Ibuprofen',
    tradeNames: ['Esrufen', 'Flamex', 'Inflam', 'Profen', 'Reumafen'],
    category: 'Others',
    forms: {
      syrup: { strength: '100mg/5ml' }
    },
    dosage: {
      standard: '10mg/kg/day',
      duration: 'BD'
    },
    notes: 'For PDA: 20mg/kg/day stat, then 10mg/kg after 24hrs then 10mg/kg after another 24 hours.'
  },
  hepaBImmunoglobulin: {
    name: 'Infant of HBsAg (+ve) mother',
    category: 'Others',
    forms: {
      ampule: { strength: '0.5ml/amp' },
      hepabig: { strength: '0.5ml/amp' }
    },
    dosage: {
      engerixB: 'Total 3 doses = 0, 1st, 2nd month (Adult 3 doses = 0, 1st, 6th month)',
      hepabig: 'Within 24 hours or as early as possible'
    },
    notes: 'Procedure: 1 amp I/M in one thigh (Engerix B), 1 amp I/M in another thigh (Hepabig)'
  },
  sodiumStibogluconate: {
    name: 'Sodium Stibogluconate',
    tradeNames: ['Stibanate', 'Stibatin'],
    category: 'Others',
    forms: {
      vial: { strength: '30ml [1ml=100mg]' }
    },
    dosage: {
      standard: '20mg/kg/day',
      duration: 'OD'
    },
    notes: 'IV slowly over 1 hour after dilute with N/S (50ml)'
  },
  nifedipine: {
    name: 'Nifedipine',
    tradeNames: ['Nifin', 'Nificapgel'],
    category: 'Others',
    forms: {
      tablet: { strength: '10mg' },
      drops: { strength: '8 drops = 10mg [1 drop = 1.25mg]' }
    },
    dosage: {
      standard: '0.25-0.5mg/kg/day',
      duration: 'BD/TDS'
    }
  },
  diclofenacSodium: {
    name: 'Diclofenac Sodium',
    tradeNames: ['Voltaline', 'Clofenac', 'Diclofen'],
    category: 'Others',
    forms: {
      tablet: { strength: ['12.5mg', '50mg'] },
      suppository: { strength: ['12.5mg', '50mg'] }
    },
    dosage: {
      standard: '1-3mg/kg/day',
      duration: 'BD'
    }
  },
  prostaglandin: {
    name: 'Prostaglandin',
    category: 'Others',
    forms: {
      strength: 'Not specified'
    },
    dosage: {
      standard: '0.1μg/kg/min'
    },
    notes: 'Dilute with 1ml (500μg) in 82ml N/S\n      Then give 1ml/kg/hour - which is equal to 0.1ml/kg/min'
  },
  bloodFFP: {
    name: 'Blood/FFP',
    category: 'Others',
    dosage: {
      standard: '10-20ml/kg'
    }
  },
  aceInhibitor: {
    name: 'ACE inhibitor',
    tradeNames: ['Captopril'],
    category: 'Others',
    dosage: {
      standard: '0.25-6mg/kg/day'
    }
  },
  ktSyrup: {
    name: 'KT-Syrup',
    category: 'Others',
    forms: {
      strength: '6.5mmol/L per TSF'
    },
    dosage: {
      standard: '1-2mmol/kg/day',
      duration: 'TDS'
    }
  },
  enalapril: {
    name: 'Enalapril',
    tradeNames: ['Enaril'],
    category: 'Others',
    forms: {
      tablet: { strength: '5mg' }
    },
    dosage: {
      standard: '0.2-0.3mg/kg/day',
      duration: 'BD'
    }
  }
};

    paracetamol: {
        name: 'Paracetamol',
        tradeNames: ['Ace', 'Napa', 'Tylenol'],
        category: 'Antipyretic/Analgesic',
        forms: {
            syrup: { 
                strength: '120mg/5ml', 
                dosePerKg: 15,
                concentration: '24mg/ml'
            },
            tablet: { 
                strength: '500mg', 
                dosePerKg: 15 
            },
            drops: { 
                strength: '80mg/ml', 
                dosePerKg: 15,
                dropsPerMl: 15
            },
            suppository: { 
                strength: '125mg', 
                dosePerKg: 15 
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '4-6',
        maxDailyDose: 60,
        notes: 'Do not exceed 4g per day in adults'
    },
    ibuprofen: {
        name: 'Ibuprofen',
        tradeNames: ['Brufen', 'Profen', 'Nurofen'],
        category: 'NSAID',
        forms: {
            syrup: { 
                strength: '100mg/5ml',
                dosePerKg: 10,
                concentration: '20mg/ml'
            },
            tablet: { 
                strength: '200mg',
                dosePerKg: 10 
            },
            drops: { 
                strength: '40mg/ml',
                dosePerKg: 10,
                dropsPerMl: 15
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '6-8',
        maxDailyDose: 30,
        notes: 'Take with food. Avoid if asthmatic'
    },
    amoxicillin: {
        name: 'Amoxicillin',
        tradeNames: ['Moxacil', 'Tycil', 'Amoxil'],
        category: 'Antibiotic',
        forms: {
            syrup: { 
                strength: '250mg/5ml',
                dosePerKg: 20,
                concentration: '50mg/ml'
            },
            tablet: { 
                strength: '500mg',
                dosePerKg: 20 
            },
            drops: { 
                strength: '100mg/ml',
                dosePerKg: 20,
                dropsPerMl: 15
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 60,
        notes: 'Complete full course as prescribed'
    },
    azithromycin: {
        name: 'Azithromycin',
        tradeNames: ['Zithromax', 'Azith', 'Zimax'],
        category: 'Antibiotic',
        forms: {
            syrup: { 
                strength: '200mg/5ml',
                dosePerKg: 10,
                concentration: '40mg/ml'
            },
            tablet: { 
                strength: '250mg',
                dosePerKg: 10 
            }
        },
        weightBased: true,
        maxDosePerDay: 1,
        interval: '24',
        maxDailyDose: 10,
        notes: 'Take on empty stomach'
    },
    cefixime: {
        name: 'Cefixime',
        tradeNames: ['Cef-3', 'Fixim', 'Suprax'],
        category: 'Antibiotic',
        forms: {
            syrup: { 
                strength: '100mg/5ml',
                dosePerKg: 8,
                concentration: '20mg/ml'
            },
            tablet: { 
                strength: '200mg',
                dosePerKg: 8 
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 16,
        notes: 'Can be taken with or without food'
    },
    salbutamol: {
        name: 'Salbutamol',
        tradeNames: ['Ventolin', 'Sultolin', 'Asthalin'],
        category: 'Bronchodilator',
        forms: {
            syrup: { 
                strength: '2mg/5ml',
                standardDose: {
                    '<2years': '2.5ml',
                    '2-5years': '5ml',
                    '>5years': '10ml'
                }
            },
            nebulization: { 
                strength: '5mg/ml',
                standardDose: {
                    '<2years': '0.25ml',
                    '2-5years': '0.5ml',
                    '>5years': '1ml'
                }
            },
            inhaler: { strength: '100mcg/puff' }
        },
        weightBased: false,
        maxDosePerDay: 4,
        interval: '6-8',
        notes: 'Monitor heart rate during nebulization'
    },
    domperidone: {
        name: 'Domperidone',
        tradeNames: ['Motilium', 'Domstal', 'Omidon'],
        category: 'Antiemetic',
        forms: {
            syrup: { 
                strength: '5mg/5ml',
                dosePerKg: 0.25,
                concentration: '1mg/ml'
            },
            tablet: { 
                strength: '10mg',
                dosePerKg: 0.25 
            },
            drops: { 
                strength: '5mg/ml',
                dosePerKg: 0.25,
                dropsPerMl: 15
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 1,
        notes: 'Take before meals'
    },
    ondansetron: {
        name: 'Ondansetron',
        tradeNames: ['Zofran', 'Emeset', 'Ondan'],
        category: 'Antiemetic',
        forms: {
            syrup: { 
                strength: '4mg/5ml',
                dosePerKg: 0.15,
                concentration: '0.8mg/ml'
            },
            tablet: { 
                strength: '4mg',
                dosePerKg: 0.15 
            },
            injection: { 
                strength: '2mg/ml',
                dosePerKg: 0.15 
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 0.5,
        notes: 'Can cause headache'
    },
    cetirizine: {
        name: 'Cetirizine',
        tradeNames: ['Zyrtec', 'Alatrol', 'Cetrin'],
        category: 'Antihistamine',
        forms: {
            syrup: { 
                strength: '5mg/5ml',
                standardDose: {
                    '6-12months': '2.5ml',
                    '1-2years': '2.5ml',
                    '2-5years': '5ml',
                    '>5years': '10ml'
                }
            },
            tablet: { strength: '10mg' }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'May cause drowsiness'
    },
    chlorpheniramine: {
        name: 'Chlorpheniramine',
        tradeNames: ['Piriton', 'Alergin', 'Histacin'],
        category: 'Antihistamine',
        forms: {
            syrup: { 
                strength: '2mg/5ml',
                standardDose: {
                    '2-5years': '5ml',
                    '6-12years': '10ml'
                }
            },
            tablet: { strength: '4mg' }
        },
        weightBased: false,
        maxDosePerDay: 4,
        interval: '6',
        notes: 'May cause significant drowsiness'
    },
    dextromethorphan: {
        name: 'Dextromethorphan',
        tradeNames: ['Robitussin', 'Dextrogen', 'Tussidex'],
        category: 'Antitussive',
        forms: {
            syrup: { 
                strength: '15mg/5ml',
                standardDose: {
                    '6-12years': '5-10ml',
                    '>12years': '10ml'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 4,
        interval: '6-8',
        notes: 'For dry cough only'
    },
    prednisolone: {
        name: 'Prednisolone',
        tradeNames: ['Predone', 'Predo', 'Deltacortril'],
        category: 'Corticosteroid',
        forms: {
            syrup: { strength: '5mg/5ml', dosePerKg: 1 },
            tablet: { strength: '5mg', dosePerKg: 1 }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12-24',
        maxDailyDose: 2,
        notes: 'Take with food. Do not stop abruptly'
    },
    albendazole: {
        name: 'Albendazole',
        tradeNames: ['Zentel', 'Almex', 'Albend'],
        category: 'Antihelminthic',
        forms: {
            syrup: { 
                strength: '200mg/5ml',
                standardDose: {
                    '<2years': '200mg',
                    '>2years': '400mg'
                }
            },
            tablet: { strength: '400mg' }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        duration: 'Single dose, may repeat after 2 weeks',
        notes: 'Take with fatty meal for better absorption'
    },
    ironSulfate: {
        name: 'Iron Sulfate',
        tradeNames: ['Ferous', 'Feromin', 'Fersolate'],
        category: 'Iron Supplement',
        forms: {
            syrup: { strength: '60mg/5ml', dosePerKg: 6 },
            drops: { strength: '75mg/ml', dosePerKg: 6, dropsPerMl: 15 }
        },
        weightBased: true,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'Take on empty stomach. May cause dark stools'
    },
    fluconazole: {
        name: 'Fluconazole',
        tradeNames: ['Diflucan', 'Flugal', 'Forcan'],
        category: 'Antifungal',
        forms: {
            syrup: { strength: '50mg/5ml', dosePerKg: 6 },
            capsule: { strength: '150mg', dosePerKg: 6 }
        },
        weightBased: true,
        maxDosePerDay: 1,
        interval: '24',
        maxDailyDose: 12,
        notes: 'Duration depends on infection type'
    },
    clarithromycin: {
        name: 'Clarithromycin',
        tradeNames: ['Klacid', 'Claricin', 'Klaricid'],
        category: 'Antibiotic',
        forms: {
            syrup: { strength: '125mg/5ml', dosePerKg: 7.5 },
            tablet: { strength: '250mg', dosePerKg: 7.5 }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 15,
        notes: 'Take with or without food'
    },
    cefuroxime: {
        name: 'Cefuroxime',
        tradeNames: ['Zinacef', 'Cefurim', 'Zinnat'],
        category: 'Antibiotic',
        forms: {
            syrup: { strength: '125mg/5ml', dosePerKg: 10 },
            tablet: { strength: '250mg', dosePerKg: 10 }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 20,
        notes: 'Take with food for better absorption'
    }
    // Add more drugs here...
};

// Add category filter function
function filterByCategory(category) {
    return Object.entries(drugDatabase)
        .filter(([_, drug]) => drug.category === category)
        .map(([key, drug]) => ({
            key: key,
            name: drug.name,
            tradeNames: drug.tradeNames
        }));
}

// Enhanced search function with category support
function searchDrug(query, category = null) {
    query = query.toLowerCase();
    return Object.entries(drugDatabase)
        .filter(([key, drug]) => {
            const matchesSearch = key.includes(query) || 
                                drug.name.toLowerCase().includes(query) ||
                                drug.tradeNames.some(name => name.toLowerCase().includes(query));
            const matchesCategory = !category || drug.category === category;
            return matchesSearch && matchesCategory;
        })
        .map(([key, drug]) => ({
            key: key,
            name: drug.name,
            category: drug.category,
            tradeNames: drug.tradeNames
        }));
}

// Calculate dose based on weight or age
function calculateDose(weight, age, medication, form) {
    const med = drugDatabase[medication];
    const formInfo = med.forms[form];
    let doseText = '';
    let warningText = '';

    if (med.weightBased && !weight) {
        return 'Weight is required for this medication';
    }

    if (med.weightBased) {
        const totalDose = weight * formInfo.dosePerKg;
        
        // Check maximum daily dose
        if (med.maxDailyDose) {
            const dailyDose = totalDose * med.maxDosePerDay;
            if (dailyDose > (weight * med.maxDailyDose)) {
                warningText = `<div class="alert alert-warning">Warning: Calculated daily dose exceeds maximum recommended dose of ${med.maxDailyDose}mg/kg/day</div>`;
            }
        }

        switch(form) {
            case 'syrup':
                const mlDose = (totalDose * 5) / parseInt(formInfo.strength);
                const tspDose = mlDose / 5;
                doseText = `
                    <p>Single Dose: ${totalDose.toFixed(1)}mg (${mlDose.toFixed(1)}ml or ${tspDose.toFixed(1)} teaspoons)</p>
                    <p>Given every ${med.interval} hours</p>
                    <p>Strength: ${formInfo.strength}</p>
                    <p>Daily Dose: ${(totalDose * med.maxDosePerDay).toFixed(1)}mg</p>
                `;
                break;
                
            case 'drops':
                const dropDose = (totalDose / parseInt(formInfo.strength)) * formInfo.dropsPerMl;
                doseText = `
                    <p>Single Dose: ${totalDose.toFixed(1)}mg (${dropDose.toFixed(0)} drops)</p>
                    <p>Given every ${med.interval} hours</p>
                    <p>Strength: ${formInfo.strength}</p>
                `;
                break;
                
            // Add more cases for other forms...
        }
    } else {
        // Age-based dosing
        let dosage = formInfo.standardDose;
        let ageGroup = Object.keys(dosage).find(range => {
            if (range.startsWith('<') && age < parseInt(range.slice(1))) return true;
            if (range.startsWith('>') && age > parseInt(range.slice(1))) return true;
            if (range.includes('-')) {
                let [min, max] = range.split('-').map(n => parseInt(n));
                return age >= min && age <= max;
            }
            return false;
        });
        
        doseText = `
            <p>Standard Dose: ${dosage[ageGroup]}</p>
            <p>Given every ${med.interval} hours</p>
            <p>Maximum ${med.maxDosePerDay} doses per day</p>
        `;
    }

    return `
        <div class="alert alert-info">
            <h5 class="alert-heading">${med.name} Dosing</h5>
            ${doseText}
            ${med.notes ? `<p class="text-warning"><strong>Note:</strong> ${med.notes}</p>` : ''}
            ${warningText}
        </div>
    `;
}

document.getElementById('doseCalculator').addEventListener('submit', function(e) {
    e.preventDefault();
    const weight = parseFloat(document.getElementById('weight').value);
    const selectedMedicationKey = document.getElementById('medication').value;
    const selectedForm = document.getElementById('medicationForm').value;
    const selectedMedication = drugDatabase[selectedMedicationKey];

    if (selectedMedication && selectedForm && weight) {
        const dose = calculateDose(selectedMedication, selectedForm, weight);
        document.getElementById('result').innerHTML = `Calculated Dose: ${dose}`;
    } else {
        document.getElementById('result').innerHTML = 'Please select a medication, form, and enter a valid weight.';
    }
});

function calculateDose(medication, form, weight) {
    const formInfo = medication.forms[form];
    const dosePerKg = formInfo.dosePerKg;
    const totalDoseMg = (dosePerKg * weight).toFixed(2);

    if (form === 'syrup') {
        const concentration = parseFloat(formInfo.strength.split('mg/')[0]);
        const totalDoseMl = (totalDoseMg / concentration * 5).toFixed(2); // Correct conversion to ml
        const totalDoseTsf = (totalDoseMl / 5).toFixed(2); // Convert ml to TSF
        return `
            <p>Single Dose: ${totalDoseMg} mg (${totalDoseMl} ml or ${totalDoseTsf} TSF)</p>
            <p>Strength: ${formInfo.strength} (1 TSF = 5 ml)</p>
            <p>Instructions: ${medication.notes}</p>
        `;
    } else {
        return `
            <p>Single Dose: ${totalDoseMg} mg</p>
            <p>Instructions: ${medication.notes}</p>
        `;
    }
}

// Update form options when medication changes
document.getElementById('medication').addEventListener('change', function(e) {
    const medication = e.target.value;
    const formSelect = document.getElementById('medicationForm');
    formSelect.innerHTML = '<option value="">Select form</option>';
    
    if (medication && drugDatabase[medication]) {
        Object.keys(drugDatabase[medication].forms).forEach(form => {
            formSelect.innerHTML += `<option value="${form}">${form.charAt(0).toUpperCase() + form.slice(1)}</option>`;
        });
        formSelect.disabled = false;
    } else {
        formSelect.disabled = true;
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('drugSearch');
    const searchResults = document.getElementById('searchResults');
    const medicationSelect = document.getElementById('medication');
    const formSelect = document.getElementById('medicationForm');

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        searchResults.innerHTML = ''; // Clear previous results

        if (query.length > 0) {
            const results = searchDrug(query);
            if (results.length > 0) {
                results.forEach(drug => {
                    const resultItem = document.createElement('a');
                    resultItem.href = '#';
                    resultItem.className = 'list-group-item list-group-item-action';
                    resultItem.textContent = `${drug.name} (${drug.tradeNames.join(', ')})`;
                    resultItem.addEventListener('click', function(e) {
                        e.preventDefault();
                        populateMedicationDropdown(drug.key);
                        populateFormDropdown(drug.key);
                        searchInput.value = ''; // Clear search input
                        searchResults.innerHTML = ''; // Clear search results
                    });
                    searchResults.appendChild(resultItem);
                });
            } else {
                const noResultItem = document.createElement('div');
                noResultItem.className = 'list-group-item';
                noResultItem.textContent = 'No results found';
                searchResults.appendChild(noResultItem);
            }
        }
    });

    function populateMedicationDropdown(selectedKey) {
        medicationSelect.innerHTML = ''; // Clear previous options
        Object.entries(drugDatabase).forEach(([key, drug]) => {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = `${drug.name} (${drug.tradeNames.join(', ')})`;
            if (key === selectedKey) {
                option.selected = true;
            }
            medicationSelect.appendChild(option);
        });
    }

    function populateFormDropdown(selectedKey) {
        formSelect.innerHTML = ''; // Clear previous options
        const selectedMedication = drugDatabase[selectedKey];
        if (selectedMedication) {
            Object.keys(selectedMedication.forms).forEach(form => {
                const option = document.createElement('option');
                option.value = form;
                option.textContent = form.charAt(0).toUpperCase() + form.slice(1);
                formSelect.appendChild(option);
            });
            formSelect.disabled = false;
        } else {
            formSelect.disabled = true;
        }
    }
});

function searchDrug(query) {
    return Object.entries(drugDatabase)
        .filter(([key, drug]) => {
            return key.includes(query) || 
                   drug.name.toLowerCase().includes(query) ||
                   drug.tradeNames.some(name => name.toLowerCase().includes(query));
        })
        .map(([key, drug]) => ({
            key: key,
            name: drug.name,
            tradeNames: drug.tradeNames
        }));
}


function calculateBMI() {
    const heightFeet = parseFloat(document.getElementById('heightFeet').value) || 0;
    const heightInch = parseFloat(document.getElementById('heightInch').value) || 0;
    const weightKg = parseFloat(document.getElementById('weightKg').value);

    // Validate input
    if (weightKg <= 0 || (heightFeet === 0 && heightInch === 0)) {
        document.getElementById('bmiResult').innerHTML = `
            <div class="alert alert-danger">Please enter valid height and weight values.</div>
        `;
        return;
    }

    // Convert height to meters
    const heightM = ((heightFeet * 12) + heightInch) * 0.0254;
    const bmi = weightKg / (heightM * heightM);

    let category = "";
    let adviceEn = "";
    let adviceBn = "";

    // Determine BMI category and suggestions
    if (bmi < 18.5) {
        category = "Underweight";
        adviceEn = "Increase your calorie intake with nutritious foods.";
        adviceBn = "পুষ্টিকর খাবার খেয়ে ক্যালোরি গ্রহণ বাড়ান।";
    } else if (bmi >= 18.5 && bmi < 24.9) {
        category = "Normal";
        adviceEn = "Maintain your current diet and exercise regularly.";
        adviceBn = "আপনার বর্তমান ডায়েট বজায় রাখুন এবং নিয়মিত ব্যায়াম করুন।";
    } else if (bmi >= 25 && bmi < 29.9) {
        category = "Overweight";
        adviceEn = "Adopt a calorie deficit diet and increase physical activity.";
        adviceBn = "ক্যালোরি কমানোর ডায়েট এবং শারীরিক কার্যকলাপ বাড়ানোর চেষ্টা করুন।";
    } else {
        category = "Obese";
        adviceEn = "Consult a healthcare provider for professional advice.";
        adviceBn = "একজন স্বাস্থ্য বিশেষজ্ঞের সাথে পরামর্শ করুন।";
    }

    // Display result
    document.getElementById('bmiResult').innerHTML = `
        <div class="alert alert-success">
            <h5>BMI Result: ${bmi.toFixed(2)} (${category})</h5>
            <p><strong>Advice (English):</strong> ${adviceEn}</p>
            <p><strong>পরামর্শ (বাংলা):</strong> ${adviceBn}</p>
        </div>
    `;
}
