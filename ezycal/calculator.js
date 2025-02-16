const drugDatabase = {
    ampicillin: {
        name: 'Ampicillin',
        tradeNames: ['Ampexin', 'Acmeicillin', 'Ficillin', 'Pen-A'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '250mg/2.5ml',
                dosePerKg: 33.3,
                concentration: '100mg/ml'
            },
            capsule: {
                strength: '250mg',
                dosePerKg: 33.3
            },
            syrup: {
                strength: '125mg/5ml',
                dosePerKg: 33.3,
                concentration: '25mg/ml'
            },
            drops: {
                strength: '125mg/1.25ml',
                dosePerKg: 33.3,
                concentration: '100mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 100,
        notes: 'Neonates: 12 hourly dosing'
    },
    gentamicin: {
        name: 'Gentamycin',
        tradeNames: ['Gentin', 'Genacyn', 'Invigen'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '20mg/2ml',
                dosePerKg: 1.67,
                concentration: '10mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 5,
        notes: 'Monitor renal function'
    },
    amikacin: {
        name: 'Amikacin',
        tradeNames: ['Kacin', 'Amibac', 'Amistar'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '100mg/2ml',
                dosePerKg: 5,
                concentration: '50mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 15,
        notes: 'Caution: Renal impairment'
    },
    ceftazidime: {
        name: 'Ceftazidime',
        tradeNames: ['Tazid', 'Zitum', 'Serozid', 'Sidobac', 'Trum-3', 'Cefazid'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '1g/10ml',
                dosePerKg: 50,
                concentration: '100mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 150,
        notes: 'For meningitis: 150mg/kg/day'
    },
    cefotaxime: {
        name: 'Cefotaxime',
        tradeNames: ['Maxcef', 'Cefotime', 'Taxim', 'Cefotex'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '1g/10ml',
                dosePerKg: 50,
                concentration: '100mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 150,
        notes: 'For meningitis: 150mg/kg/day'
    },
    ceftriaxone: {
        name: 'Ceftriaxone',
        tradeNames: ['Ceftron', 'Dicephin', 'Roficin', 'Oricef', 'Arixon', 'Axon', 'Traxef', 'Ceftizone'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '1g/10ml',
                dosePerKg: 50,
                concentration: '100mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 100,
        notes: 'Meningitis dose: 100mg/kg/day'
    },
    imepenem: {
        name: 'Imipenem',
        tradeNames: ['Imenem', 'Cispenam'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '500mg/10ml',
                dosePerKg: 20,
                concentration: '50mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 60,
        notes: 'Dilute with 10ml of IV fluid'
    },
    meropenem: {
        name: 'Meropenem',
        tradeNames: ['Spacbac', 'I-Penem', 'Fulspec', 'Ropenam', 'Neopenam', 'Merocon', 'Meropen'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '500mg/10ml',
                dosePerKg: 20,
                concentration: '50mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 60,
        notes: 'Dilute with 10ml of IV fluid'
    },
    vancomycin: {
        name: 'Vancomycin',
        tradeNames: ['Vancomycin', 'Vanmycin', 'Vancomin', 'Covan'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '500mg/10ml',
                dosePerKg: 7.5,
                concentration: '50mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6',
        maxDailyDose: 15,
        notes: '<7 days: 15mg/kg/dose 12 hourly/BD, >7 days: 15mg/kg/dose 8 hourly/TDS'
    },
    flucloxacillin: {
        name: 'Flucloxacillin',
        tradeNames: ['Fluclox', 'Phylopen', 'Phylopen Forte DS Flux', 'Flubex', 'Flupen', 'Flubac'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '125mg/5ml',
                dosePerKg: 25,
                concentration: '25mg/ml'
            },
            tablet: {
                strength: '250mg',
                dosePerKg: 25
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6',
        maxDailyDose: 100,
        notes: 'Infant: 50mg/kg/day (oral). Duration: QDS'
    },
    cephradine: {
        name: 'Cephradine',
        tradeNames: ['Dicef', 'Cephran'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '125mg/5ml',
                dosePerKg: 20,
                concentration: '25mg/ml'
            },
            tablet: {
                strength: '250mg',
                dosePerKg: 20
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6',
        maxDailyDose: 100,
        notes: 'Duration: QDS'
    },
    piperacillintazobactam: {
        name: 'Piperacillin+Tazobactam',
        tradeNames: ['Megacillin', 'Tazopen'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '4.5g/20ml',
                dosePerKg: 100,
                concentration: '225mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 300,
        notes: 'Duration: 8 hourly'
    },
    amoxicillin: {
        name: 'Amoxicillin',
        tradeNames: ['Moxacil', 'Moxin', 'Tycil', 'Fimoxyl'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '125mg/5ml',
                dosePerKg: 40,
                concentration: '25mg/ml'
            },
            tablet: {
                strength: '250mg',
                dosePerKg: 40
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 120,
        notes: 'Duration: TDS'
    },
    amoxicillinclavulanicacid: {
        name: 'Amoxicillin+Clavulanic Acid',
        tradeNames: ['Moxaclav', 'Fimoxyclav', 'Ticlav'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '156mg/5ml',
                dosePerKg: 40,
                concentration: '31.2mg/ml'
            },
            tablet: {
                strength: '375mg',
                dosePerKg: 40
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 120,
        notes: 'Neonate: 30mg/kg/day. Duration: TDS (Neonate: BD)'
    },
    cefuroxime: {
        name: 'Cefuroxime',
        tradeNames: ['Cefotil', 'Kilbac', 'Sefur', 'Cefobac', 'Rofurox', 'Sefurox', 'Cerox-A', 'Furocef'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '250mg/2.5ml',
                dosePerKg: 20,
                concentration: '100mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 60,
        notes: 'Duration: 12 hourly'
    },
    ciprofloxacin: {
        name: 'Ciprofloxacin',
        tradeNames: ['Ciprocin', 'Neofloxin', 'Flontin', 'Beuflox', 'Ciprox', 'Cipro-A', 'X-bac'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '250mg/5ml',
                dosePerKg: 15,
                concentration: '50mg/ml'
            },
            tablet: {
                strength: '250mg',
                dosePerKg: 15
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 30,
        notes: 'Duration: BD'
    },
    azithromycin: {
        name: 'Azithromycin',
        tradeNames: ['Zimax', 'Azith', 'Zithromax'],
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
        notes: '3-day course for most infections'
    },
    cefixime: {
        name: 'Cefixime',
        tradeNames: ['Cef-3', 'Denver', 'T-Cef'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '100mg/5ml',
                dosePerKg: 4,
                concentration: '20mg/ml'
            },
            tablet: {
                strength: '200mg',
                dosePerKg: 4
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 16,
        notes: 'Administer with or without food'
    },
    cefepime: {
        name: 'Cefepime',
        tradeNames: ['Ceftipime', 'Maxipime', 'Xenim', 'Ultrapime'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '500mg/10ml',
                dosePerKg: 50,
                concentration: '50mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 100,
        notes: '<14 days: 30 mg/kg/day, >14 days: 50 mg/kg/day'
    },
    cephalexin: {
        name: 'Cephalexin',
        tradeNames: ['Ceftipime', 'Cephalen', 'Ceporin', 'Neorex'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '125mg/5ml',
                dosePerKg: 25,
                concentration: '25mg/ml'
            },
            tablet: {
                strength: '250mg',
                dosePerKg: 25
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6',
        maxDailyDose: 100,
        notes: '<7 days: 12 hourly dosing, >7 days: 8 hourly dosing'
    },
    cefpodoxime: {
        name: 'Cefpodoxime',
        tradeNames: ['Vanprox', 'Ximprox', 'Vercef', 'Kidcef', 'Roxitil', 'Trucef'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '100mg/5ml',
                dosePerKg: 10,
                concentration: '20mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 20,
        notes: 'Duration: BD'
    },
    netilmicin: {
        name: 'Netilmicin',
        tradeNames: ['Netromycin'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '100mg/2ml',
                dosePerKg: 5,
                concentration: '50mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 15,
        notes: 'Used in neonates after first week'
    },
    colomycin: {
        name: 'Colomycin',
        tradeNames: ['Colistin'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '1 million units/2ml',
                dosePerKg: 25000,
                concentration: '500000 units/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 75000,
        notes: 'Duration: 8 hourly'
    },
    clindamycin: {
        name: 'Clindamycin',
        tradeNames: ['Clindex', 'Clindacin', 'Climycin'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '75mg/5ml',
                dosePerKg: 3,
                concentration: '15mg/ml'
            },
            tablet: {
                strength: '150mg',
                dosePerKg: 3
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6',
        maxDailyDose: 12,
        notes: '0-14 days: 8 hourly/TDS, 15 days-12 yrs: 6 hourly/QDS'
    },
    erythromycin: {
        name: 'Erythromycin',
        tradeNames: ['Eromycin', 'A-mycin', 'Erythrox'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '125mg/5ml',
                dosePerKg: 50,
                concentration: '25mg/ml'
            },
            tablet: {
                strength: '250mg',
                dosePerKg: 50
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6',
        maxDailyDose: 200,
        notes: 'Duration: QDS'
    },
    metronidazole: {
        name: 'Metronidazole',
        tradeNames: ['Amodis', 'Flamyd', 'Flazyl', 'Metro', 'Menz', 'Metryl', 'Filmet'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '200mg/5ml',
                dosePerKg: 20,
                concentration: '40mg/ml'
            },
            tablet: {
                strength: '250mg',
                dosePerKg: 20
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 60,
        notes: 'Duration: TDS'
    },
    acyclovir: {
        name: 'Acyclovir',
        tradeNames: ['Zovirux', 'Xovir', 'Virux', 'Novirux'],
        category: 'Antiviral',
        forms: {
            syrup: {
                strength: '200mg/5ml',
                dosePerKg: 10,
                concentration: '40mg/ml'
            },
            tablet: {
                strength: '200mg',
                dosePerKg: 10
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 30,
        notes: 'Duration: TDS'
    },
    ganciclovir: {
        name: 'Ganciclovir',
        tradeNames: ['Cymevene', 'Cytovene'],
        category: 'Antiviral',
        forms: {
            injection: {
                strength: '5mg/ml',
                dosePerKg: 6,
                concentration: '5mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 18,
        notes: 'Duration: 12 hourly'
    },
    fluconazole: {
        name: 'Fluconazole',
        tradeNames: ['Diflucan', 'Flugal', 'Forcan'],
        category: 'Antifungal',
        forms: {
            syrup: {
                strength: '100mg/5ml',
                dosePerKg: 12,
                concentration: '20mg/ml'
            },
            tablet: {
                strength: '100mg',
                dosePerKg: 12
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 36,
        notes: 'Administer once daily'
    },
    nystatin: {
        name: 'Nystatin',
        tradeNames: ['Nystat', 'Candex', 'Fungistin', 'Naf'],
        category: 'Antifungal',
        forms: {
            suspension: {
                strength: '100,000 units/ml',
                standardDose: {
                    '<2months': '1ml',
                    '2-12months': '1.5ml',
                    '>1year': '2ml'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 4,
        interval: '6',
        notes: 'Duration: TDS/QDS'
    },
    chloroquine: {
        name: 'Chloroquine',
        tradeNames: ['Avloquin', 'Jesochlor', 'Base'],
        category: 'Antimalarial',
        forms: {
            tablet: {
                strength: '100mg',
                standardDose: {
                    'day1': '10mg/kg',
                    'days2-3': '5mg/kg'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'Not for pediatric use without supervision'
    },
    primaquin: {
        name: 'Primaquin',
        tradeNames: ['Primaquin'],
        category: 'Antimalarial',
        forms: {
            tablet: {
                strength: '7.5mg',
                standardDose: {
                    'adults': '0.9mg/kg'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'For adjunctive therapy'
    },
    cotrimoxazole: {
        name: 'Cotrimoxazole',
        tradeNames: ['Bactrim', 'Septra'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '240mg (S) + 120mg (T)/5ml',
                standardDose: {
                    '<2months': '1ml/kg',
                    '2-12months': '1.5ml/kg'
                }
            },
            tablet: {
                strength: '800mg (S) + 400mg (T)',
                standardDose: {
                    'adults': '1 tablet BD'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 2,
        interval: '12',
        notes: 'Used in HIV-positive patients'
    },
    quinine: {
        name: 'Quinine',
        tradeNames: ['Quinsulf', 'Quinimax'],
        category: 'Antimalarial',
        forms: {
            injection: {
                strength: '300mg/10ml',
                dosePerKg: 20,
                concentration: '30mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 60,
        notes: 'Monitor ECG for QT prolongation'
    },
    pentaglobulin: {
        name: 'Pentaglobulin',
        tradeNames: ['Pentaglobulin'],
        category: 'Immunoglobulin',
        forms: {
            vial: {
                strength: '1g',
                standardDose: {
                    'total': '0.25g/kg or 5ml/kg'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'Administered for bleeding disorders'
    },
    nitrazoxanide: {
        name: 'Nitrazoxanide',
        tradeNames: ['Zox Nitazox'],
        category: 'Antiparasitic',
        forms: {
            syrup: {
                strength: '100mg/5ml',
                standardDose: {
                    '6-12months': '1ml (50mg) QD',
                    '1-4years': '2ml (100mg) QD',
                    '4-11years': '4ml (200mg) QD'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'For treatment of Giardia and Cryptosporidium'
    },
    ivig: {
        name: 'IVIG',
        tradeNames: ['IVIG'],
        category: 'Immunoglobulin',
        forms: {
            vial: {
                strength: '50mg/ml',
                standardDose: {
                    'total': '1g/kg over 3 days'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'Used in immune thrombocytopenic purpura'
    },
    clarithromycin: {
        name: 'Clarithromycin',
        tradeNames: ['Biaxin', 'Klaricid'],
        category: 'Antibiotic',
        forms: {
            syrup: {
                strength: '125mg/5ml',
                dosePerKg: 15,
                concentration: '25mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 30,
        notes: 'May interact with other drugs'
    },
    chloramphenicol: {
        name: 'Chloramphenicol',
        tradeNames: ['Chloromycetin'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '250mg/ml',
                dosePerKg: 25,
                concentration: '250mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6',
        maxDailyDose: 100,
        notes: 'Gray baby syndrome risk'
    },
    domperidone: {
        name: 'Domperidone',
        tradeNames: ['Domin', 'Omidon', 'Motigut'],
        category: 'Antiemetic',
        forms: {
            syrup: {
                strength: '5mg/5ml',
                dosePerKg: 0.4,
                concentration: '1mg/ml'
            },
            tablet: {
                strength: '10mg',
                dosePerKg: 0.4
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6-8',
        notes: 'Administer 15-30 minutes before meals'
    },
    ondansetron: {
        name: 'Ondansetron',
        tradeNames: ['Zofran', 'Ondaz', 'Anzemet'],
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
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        notes: 'For chemotherapy-induced vomiting'
    },
    diazepam: {
        name: 'Diazepam',
        tradeNames: ['Valium'],
        category: 'Antianxiety',
        forms: {
            injection: {
                strength: '5mg/ml',
                dosePerKg: 0.3,
                concentration: '5mg/ml'
            },
            oral: {
                strength: '5mg',
                dosePerKg: 0.5
            }
        },
        weightBased: true,
        maxDosePerDay: 1,
        interval: '12',
        maxDailyDose: 5,
        notes: 'Not for pediatric use without supervision'
    },
    phenobarbitone: {
        name: 'Phenobarbitone',
        tradeNames: ['Luminal'],
        category: 'Anticonvulsant',
        forms: {
            syrup: {
                strength: '20mg/5ml',
                dosePerKg: 3,
                concentration: '4mg/ml'
            },
            tablet: {
                strength: '30mg',
                dosePerKg: 3
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6',
        maxDailyDose: 12,
        notes: 'Duration: BD'
    },
    fosphenytoin: {
        name: 'Fosphenytoin',
        tradeNames: ['Cerebyx'],
        category: 'Anticonvulsant',
        forms: {
            injection: {
                strength: '20mg/ml',
                dosePerKg: 15,
                concentration: '10mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 30,
        notes: 'Used as prodrug for phenytoin'
    },
    midazolam: {
        name: 'Midazolam',
        tradeNames: ['Hypnovel', 'Dormicum'],
        category: 'Sedative',
        forms: {
            injection: {
                strength: '5mg/ml',
                dosePerKg: 0.1,
                concentration: '5mg/ml'
            },
            syrup: {
                strength: '2.5mg/ml',
                dosePerKg: 0.1,
                concentration: '1mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6',
        maxDailyDose: 4,
        notes: 'For procedural sedation'
    },
    clonazepam: {
        name: 'Clonazepam',
        tradeNames: ['Klonopin'],
        category: 'Anticonvulsant',
        forms: {
            tablet: {
                strength: '0.5mg',
                dosePerKg: 0.02,
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 0.06,
        notes: 'Administer with caution in infants'
    },
    sodiumvalproate: {
        name: 'Sodium Valproate',
        tradeNames: ['Depakote', 'Convulex'],
        category: 'Anticonvulsant',
        forms: {
            syrup: {
                strength: '200mg/5ml',
                dosePerKg: 10,
                concentration: '40mg/ml'
            },
            tablet: {
                strength: '200mg',
                dosePerKg: 10
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 30,
        notes: 'Monitor liver function tests'
    },
    pyrantalpamoet: {
        name: 'Pyrantal Pamoet',
        tradeNames: ['Eimox', 'Delentin'],
        category: 'Antihelminthic',
        forms: {
            orally: {
                strength: '100mg/kg',
                standardDose: {
                    '<1year': 'Not recommended',
                    '1-5years': '100mg once',
                    '5-15years': '200mg once'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'For pinworm infestation'
    },
    mebendazole: {
        name: 'Mebendazole',
        tradeNames: ['Vermox', 'G(blob)'],
        category: 'Antihelminthic',
        forms: {
            orally: {
                strength: '100mg',
                standardDose: {
                    '2-12years': '100mg once',
                    '>12years': '200mg once'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'For roundworm infestation'
    },
    albendazole: {
        name: 'Albendazole',
        tradeNames: ['Albenza', 'Zentel'],
        category: 'Antihelminthic',
        forms: {
            orally: {
                strength: '400mg',
                standardDose: {
                    '<2years': '200mg single dose',
                    '>2years': '400mg single dose'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'For tapeworm infestation'
    },
    paracetamol: {
        name: 'Paracetamol',
        tradeNames: ['Tylenol', 'Pamol', 'Panado'],
        category: 'Analgesic',
        forms: {
            syrup: {
                strength: '120mg/5ml',
                dosePerKg: 15,
                concentration: '24mg/ml'
            },
            tablet: {
                strength: '500mg',
                dosePerKg: 15
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '4-6',
        maxDailyDose: 60,
        notes: 'Do not exceed 4g/day in adults'
    },
    ranitidine: {
        name: 'Ranitidine',
        tradeNames: ['Zantac'],
        category: 'Antacid',
        forms: {
            syrup: {
                strength: '75mg/5ml',
                dosePerKg: 2,
                concentration: '15mg/ml'
            },
            tablet: {
                strength: '150mg',
                dosePerKg: 2
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 4,
        notes: 'Adjust dose in renal impairment'
    },
    aminophylline: {
        name: 'Aminophylline',
        tradeNames: ['Phyllocontin'],
        category: 'Bronchodilator',
        forms: {
            injection: {
                strength: '125mg/5ml',
                dosePerKg: 5,
                concentration: '25mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 15,
        notes: 'Monitor serum theophylline levels'
    },
    adrenaline: {
        name: 'Adrenaline',
        tradeNames: ['Epinephrine'],
        category: 'Resuscitation',
        forms: {
            injection: {
                strength: '1mg/ml',
                standardDose: {
                    'neonates': '0.01mg/kg',
                    'children': '0.01mg/kg'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: 'PRN',
        notes: 'For anaphylaxis or cardiac arrest'
    },
    dopamine: {
        name: 'Dopamine',
        tradeNames: ['Intropin'],
        category: 'Vasopressor',
        forms: {
            injection: {
                strength: '400mg/10ml',
                dosePerKg: 5,
                concentration: '40mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '1',
        maxDailyDose: 15,
        notes: 'For hypotension'
    },
    digoxin: {
        name: 'Digoxin',
        tradeNames: ['Lanoxin'],
        category: 'Cardiac Glycoside',
        forms: {
            injection: {
                strength: '250mcg/ml',
                dosePerKg: 0.04,
                concentration: '0.25mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '6',
        maxDailyDose: 0.12,
        notes: 'Monitor serum digoxin levels'
    },
    calciumgluconate: {
        name: 'Calcium Gluconate',
        tradeNames: ['Calgluconate'],
        category: 'Electrolyte',
        forms: {
            injection: {
                strength: '10ml/5ml',
                standardDose: {
                    'total': '1-2ml/kg'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'For hypocalcemia'
    },
    sodiumbicarbonate: {
        name: 'Sodium Bicarbonate',
        tradeNames: ['Bicarbonate'],
        category: 'Electrolyte',
        forms: {
            injection: {
                strength: '10ml/10ml',
                dosePerKg: 1,
                concentration: '1mEq/kg'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '1',
        maxDailyDose: 3,
        notes: 'For metabolic acidosis'
    },
    frusemide: {
        name: 'Frusemide',
        tradeNames: ['Lasix'],
        category: 'Diuretic',
        forms: {
            injection: {
                strength: '10mg/ml',
                dosePerKg: 1,
                concentration: '10mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 3,
        notes: 'For fluid overload'
    },
    dexamethasone: {
        name: 'Dexamethasone',
        tradeNames: ['Decadron'],
        category: 'Corticosteroid',
        forms: {
            injection: {
                strength: '4mg/ml',
                dosePerKg: 0.2,
                concentration: '4mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 0.4,
        notes: 'For allergic reactions'
    },
    hydrocortisone: {
        name: 'Hydrocortisone',
        tradeNames: ['Hydrocortone'],
        category: 'Corticosteroid',
        forms: {
            injection: {
                strength: '50mg/2ml',
                dosePerKg: 2,
                concentration: '25mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6',
        maxDailyDose: 8,
        notes: 'For adrenal insufficiency'
    },
    prednisolone: {
        name: 'Prednisolone',
        tradeNames: ['Prednisone'],
        category: 'Corticosteroid',
        forms: {
            syrup: {
                strength: '5mg/5ml',
                dosePerKg: 1,
                concentration: '1mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 3,
        notes: 'For asthma exacerbations'
    },
    folicacid: {
        name: 'Folic Acid',
        tradeNames: ['Folvite'],
        category: 'Vitamin',
        forms: {
            tablet: {
                strength: '1mg',
                dosePerKg: 0.05
            }
        },
        weightBased: true,
        maxDosePerDay: 1,
        interval: '24',
        maxDailyDose: 0.1,
        notes: 'For megaloblastic anemia'
    },
    mannitol: {
        name: 'Mannitol',
        tradeNames: ['Osmitrol'],
        category: 'Diuretic',
        forms: {
            injection: {
                strength: '10g/20ml',
                dosePerKg: 0.25,
                concentration: '500mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 0.75,
        notes: 'For increased intracranial pressure'
    },
    iron: {
        name: 'Iron',
        tradeNames: ['Ferrous Fumarate'],
        category: 'Vitamin',
        forms: {
            syrup: {
                strength: '40mg/ml',
                dosePerKg: 3,
                concentration: '8mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 9,
        notes: 'For iron-deficiency anemia'
    },
    chlorpheniramine: {
        name: 'Chlorpheniramine',
        tradeNames: ['Chlor-Trimeton'],
        category: 'Antihistamine',
        forms: {
            syrup: {
                strength: '2mg/ml',
                dosePerKg: 0.18,
                concentration: '1mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '6',
        maxDailyDose: 0.54,
        notes: 'For allergic rhinitis'
    },
    longactingmorphine: {
        name: 'Long-Acting Morphine',
        tradeNames: ['OxyContin'],
        category: 'Analgesic',
        forms: {
            tablet: {
                strength: '10mg',
                standardDose: {
                    'children': 'Not recommended'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'Not for pediatric use'
    },
    oxcarbazepine: {
        name: 'Oxcarbazepine',
        tradeNames: ['Trileptal'],
        category: 'Anticonvulsant',
        forms: {
            tablet: {
                strength: '150mg',
                dosePerKg: 0.7,
                loadingDose: 10
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 21,
        notes: 'Adjust dose gradually'
    },
    midazolam: {
        name: 'Midazolam',
        tradeNames: ['Hypnovel'],
        category: 'Sedative',
        forms: {
            syrup: {
                strength: '2.5mg/ml',
                dosePerKg: 0.2,
                concentration: '0.5mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '1',
        maxDailyDose: 0.6,
        notes: 'For conscious sedation'
    },
    temlar: {
        name: 'Temlar',
        category: 'Analgesic',
        forms: {
            syrup: {
                strength: '7.5mg/ml',
                dosePerKg: 0.1,
                concentration: '1.5mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 0.3,
        notes: 'Not recommended for children under 6 months'
    },
    prednisolone: {
        name: 'Prednisolone',
        tradeNames: ['Prednisolone'],
        category: 'Corticosteroid',
        forms: {
            syrup: {
                strength: '15mg/5ml',
                dosePerKg: 1,
                concentration: '3mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 3,
        notes: 'For inflammatory conditions'
    },
    ranitidine: {
        name: 'Ranitidine',
        tradeNames: ['Zantac'],
        category: 'Antacid',
        forms: {
            syrup: {
                strength: '75mg/5ml',
                dosePerKg: 2,
                concentration: '15mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 6,
        notes: 'For gastroesophageal reflux disease'
    },
    aminophylline: {
        name: 'Aminophylline',
        tradeNames: ['Phyllocontin'],
        category: 'Bronchodilator',
        forms: {
            injection: {
                strength: '125mg/5ml',
                dosePerKg: 5,
                concentration: '25mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 15,
        notes: 'For asthma exacerbations'
    },
    adrenaline: {
        name: 'Adrenaline',
        tradeNames: ['Epinephrine'],
        category: 'Resuscitation',
        forms: {
            injection: {
                strength: '1mg/ml',
                dosePerKg: 0.1,
                concentration: '1mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '1',
        maxDailyDose: 0.3,
        notes: 'For cardiac arrest'
    },
    dopamine: {
        name: 'Dopamine',
        tradeNames: ['Intropin'],
        category: 'Vasopressor',
        forms: {
            injection: {
                strength: '400mg/10ml',
                dosePerKg: 5,
                concentration: '40mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '1',
        maxDailyDose: 15,
        notes: 'For hypotension'
    },
    digoxin: {
        name: 'Digoxin',
        tradeNames: ['Lanoxin'],
        category: 'Cardiac Glycoside',
        forms: {
            injection: {
                strength: '250mcg/ml',
                dosePerKg: 0.04,
                concentration: '0.25mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '6',
        maxDailyDose: 0.12,
        notes: 'For heart failure'
    },
    calciumgluconate: {
        name: 'Calcium Gluconate',
        tradeNames: ['Calglu'],
        category: 'Electrolyte',
        forms: {
            injection: {
                strength: '10ml/5ml',
                dosePerKg: 1,
                concentration: '20ml/5ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '24',
        maxDailyDose: 3,
        notes: 'For hypocalcemia'
    },
    sodiumbicarbonate: {
        name: 'Sodium Bicarbonate',
        tradeNames: ['Bicarbonate'],
        category: 'Electrolyte',
        forms: {
            injection: {
                strength: '5ml/ml',
                dosePerKg: 1,
                concentration: '1mEq/kg'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '1',
        maxDailyDose: 3,
        notes: 'For metabolic acidosis'
    },
    frusemide: {
        name: 'Frusemide',
        tradeNames: ['Lasix'],
        category: 'Diuretic',
        forms: {
            injection: {
                strength: '10mg/ml',
                dosePerKg: 1,
                concentration: '10mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 3,
        notes: 'For edema'
    },
    dexamethasone: {
        name: 'Dexamethasone',
        tradeNames: ['Decadron'],
        category: 'Corticosteroid',
        forms: {
            injection: {
                strength: '4mg/ml',
                dosePerKg: 0.2,
                concentration: '4mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 0.6,
        notes: 'For allergic reactions'
    },
    hydrocortisone: {
        name: 'Hydrocortisone',
        tradeNames: ['Solu-Cortef'],
        category: 'Corticosteroid',
        forms: {
            injection: {
                strength: '100mg/ml',
                dosePerKg: 1,
                concentration: '100mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 3,
        notes: 'For adrenal insufficiency'
    },
    prednisolone: {
        name: 'Prednisolone',
        tradeNames: ['Pediapred'],
        category: 'Corticosteroid',
        forms: {
            syrup: {
                strength: '15mg/5ml',
                dosePerKg: 1,
                concentration: '3mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 3,
        notes: 'For asthma'
    },
    folicacid: {
        name: 'Folic Acid',
        tradeNames: ['Fol Acid'],
        category: 'Vitamin',
        forms: {
            tablet: {
                strength: '5mg',
                dosePerKg: 0.1
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '24',
        maxDailyDose: 0.3,
        notes: 'For anemia'
    },
    mannitol: {
        name: 'Mannitol',
        tradeNames: ['Osmitrol'],
        category: 'Diuretic',
        forms: {
            injection: {
                strength: '10g/20ml',
                dosePerKg: 0.25,
                concentration: '500mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 0.75,
        notes: 'For cerebral edema'
    },
    iron: {
        name: 'Iron',
        tradeNames: ['Fer-In-Sol'],
        category: 'Vitamin',
        forms: {
            syrup: {
                strength: '40mg/ml',
                dosePerKg: 3,
                concentration: '8mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 9,
        notes: 'For iron deficiency'
    },
    chlorpheniramine: {
        name: 'Chlorpheniramine',
        tradeNames: ['Chlor-Trimeton'],
        category: 'Antihistamine',
        forms: {
            syrup: {
                strength: '2mg/mL',
                dosePerKg: 0.18,
                concentration: '1mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '12',
        maxDailyDose: 0.54,
        notes: 'For allergies'
    },
    longactingmorphine: {
        name: 'Long-Acting Morphine',
        tradeNames: ['Kadian'],
        category: 'Analgesic',
        forms: {
            capsule: {
                strength: '10mg',
                standardDose: {
                    'children': 'Not recommended'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'Not for pediatric use'
    }
};

// Helper function to search for drugs
function searchDrug(query) {
    const results = [];
    const searchTerm = query.toLowerCase().trim();
    
    Object.entries(drugDatabase).forEach(([key, drug]) => {
        const match = 
            drug.name.toLowerCase().includes(searchTerm) ||
            drug.tradeNames.some(t => t.toLowerCase().includes(searchTerm)) ||
            drug.category.toLowerCase().includes(searchTerm);
        
        if (match) {
            results.push({
                key: key,
                name: drug.name,
                tradeNames: drug.tradeNames,
                category: drug.category
            });
        }
    });
    
    return results;
}

// Dynamic form handling
document.getElementById('medication').addEventListener('change', function(e) {
    const formSelect = document.getElementById('medicationForm');
    formSelect.innerHTML = '<option value="">Select form</option>';
    
    if (this.value) {
        const drug = drugDatabase[this.value];
        Object.entries(drug.forms).forEach(([formName, formData]) => {
            const option = document.createElement('option');
            option.value = formName;
            option.text = `${formName.charAt(0).toUpperCase()}${formName.slice(1)} (${formData.strength})`;
            formSelect.appendChild(option);
        });
        formSelect.disabled = false;
    } else {
        formSelect.disabled = true;
    }
});

// Dose calculation
function calculateDose(weight, age, drugKey, form) {
    const drug = drugDatabase[drugKey];
    if (!drug || !weight || !form) return '';

    const formData = drug.forms[form];
    if (!formData) return `Invalid form for ${drug.name}`;

    let dosePerKg = formData.dosePerKg;
    let concentration = formData.concentration;

    // Handle standard dose if not weight-based
    if (drug.weightBased) {
        const totalPerDay = weight * dosePerKg;
        const dose = totalPerDay / parseInt(drug.maxDosePerDay);
        
        let quantity = dose;
        let unit = 'mg';

        if (formData.concentration) {
            const divisor = parseFloat(concentration) || 0;
            quantity = dose / divisor;
            unit = 'ml';
        }

        return [
            `\nDose: ${quantity.toFixed(1)} ${unit} per dose`,
            `Administer every ${drug.interval} hours`,
            drug.notes ? `\nNotes: ${drug.notes}` : '',
        ].join('\n');
    } else {
        let standardDose;
        if (age) {
            const ageStr = `${Math.floor(age)}-${Math.ceil(age)}`;
            standardDose = formData.standardDose[ageStr];
        }
        return [
            `\nStandard Dose: ${standardDose}`,
            `Administer every ${drug.interval} hours`,
            drug.notes ? `\nNotes: ${drug.notes}` : '',
        ].join('\n');
    }
}

// Populate medication dropdown
const medicationSelect = document.getElementById('medication');
Object.keys(drugDatabase).forEach(key => {
    const drug = drugDatabase[key];
    const option = new Option(`${drug.name} (${drug.tradeNames.join(', ')})`, key);
    medicationSelect.add(option);
});

// Handle form submission
document.getElementById('doseCalculator').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const weight = parseFloat(document.getElementById('weight').value);
    const age = parseFloat(document.getElementById('age').value);
    const medicationKey = document.getElementById('medication').value;
    const form = document.getElementById('medicationForm').value;

    document.getElementById('result').textContent = calculateDose(weight, age, medicationKey, form);
});

// Autocomplete functionality
document.getElementById('drugSearch').addEventListener('input', function() {
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = '';
    
    const searchTerm = this.value.toLowerCase();
    if (searchTerm.length < 2) return;

    const results = searchDrug(searchTerm);
    results.forEach(drug => {
        const option = new Option(drug.name, drug.key);
        resultsContainer.appendChild(option);
    });

    resultsContainer.style.display = results.length ? 'block' : 'none';
});

// Handle autocomplete selection
document.getElementById('searchResults').addEventListener('change', function() {
    const selectedKey = this.value;
    document.getElementById('medication').value = selectedKey;
    document.getElementById('medication').dispatchEvent(new Event('change'));
    this.innerHTML = '';
});
