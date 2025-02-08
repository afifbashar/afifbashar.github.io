const drugDatabase = {
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
            },
            drops: {
                strength: '5mg/ml',
                dosePerKg: 0.4,
                dropsPerMl: 20
            }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6-8',
        notes: 'Administer 15-30 minutes before meals'
    },

    ondansetron: {
        name: 'Ondansetron',
        tradeNames: ['Emeset', 'Onaseron', 'Ofran'],
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
        notes: 'Monitor for QT prolongation'
    },

    // ANTICONVULSANTS
    diazepam: {
        name: 'Diazepam',
        tradeNames: ['Sedil', 'Easium'],
        category: 'Anticonvulsant',
        forms: {
            rectal: {
                strength: '5mg/ml',
                dosePerKg: 0.5,
                concentration: '5mg/ml'
            },
            injection: {
                strength: '10mg/2ml',
                dosePerKg: 0.3
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: 'PRN',
        notes: 'Respiratory monitoring required'
    },

    sodiumValproate: {
        name: 'Sodium Valproate',
        tradeNames: ['Valex', 'Valpro', 'Convules'],
        category: 'Anticonvulsant',
        forms: {
            syrup: {
                strength: '200mg/5ml',
                dosePerKg: 5,
                concentration: '40mg/ml'
            },
            tablet: {
                strength: '200mg',
                dosePerKg: 5
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        notes: 'Monitor liver function tests'
    },

    // ANTIHELMINTHICS
    mebendazole: {
        name: 'Mebendazole',
        tradeNames: ['Ermox', 'Solas', 'Meben'],
        category: 'Antihelminthic',
        forms: {
            tablet: {
                strength: '100mg',
                standardDose: {
                    '1-12years': '100mg'
                }
            },
            syrup: {
                strength: '100mg/5ml',
                standardDose: {
                    '1-12years': '5ml'
                }
            }
        },
        weightBased: false,
        maxDosePerDay: 2,
        interval: '12',
        notes: 'Administer for 3 consecutive days'
    },

    pyrantelPamoate: {
        name: 'Pyrantel Pamoate',
        tradeNames: ['Delentin', 'Melphin'],
        category: 'Antihelminthic',
        forms: {
            syrup: {
                strength: '250mg/5ml',
                dosePerKg: 10,
                concentration: '50mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'Repeat dose after 2 weeks'
    },

    // GASTROINTESTINAL
    ranitidine: {
        name: 'Ranitidine',
        tradeNames: ['Ranison', 'Neotak', 'Ranidine'],
        category: 'Antacid',
        forms: {
            syrup: {
                strength: '75mg/5ml',
                dosePerKg: 2,
                concentration: '15mg/ml'
            },
            injection: {
                strength: '50mg/2ml',
                dosePerKg: 2
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        notes: 'Adjust dose in renal impairment'
    },

    // RESPIRATORY
    aminophylline: {
        name: 'Aminophylline',
        tradeNames: ['Phyllocontin', 'Truphylline'],
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
        notes: 'Monitor serum theophylline levels'
    },

    // EMERGENCY MEDICATIONS
    adrenaline: {
        name: 'Adrenaline',
        tradeNames: ['Epinephrine'],
        category: 'Emergency',
        forms: {
            injection: {
                strength: '1mg/ml (1:1000)',
                standardDose: {
                    'neonates': '0.01mg/kg',
                    'children': '0.01mg/kg'
                }
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: 'PRN',
        notes: 'IV/IM use for anaphylaxis'
    },

    // ANTIBIOTICS (Additional)
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
        notes: 'Administer with or without food'
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
        notes: '3-day course for most infections'
    }
};

// ... Rest of the JavaScript code remains unchanged ...
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
    // New entries from PDF
    ampicillin: {
        name: 'Ampicillin',
        tradeNames: ['Ampexin', 'Acmeicillin', 'Ficillin', 'Pen-A'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '250mg/2.5ml',
                dosePerKg: 33.3, // 100mg/kg/day ÷ 3 doses
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

    gentamycin: {
        name: 'Gentamycin',
        tradeNames: ['Gentin', 'Genacyn', 'Invigen'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '20mg/2ml',
                dosePerKg: 1.67, // 5mg/kg/day ÷ 3 doses
                concentration: '10mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        maxDailyDose: 5,
        notes: 'Monitor renal function'
    },

    ceftriaxone: {
        name: 'Ceftriaxone',
        tradeNames: ['Ceftron', 'Dicephin', 'Roficin'],
        category: 'Antibiotic',
        forms: {
            vial: {
                strength: '1g/10ml',
                dosePerKg: 50, // Standard 50-100mg/kg/day
                concentration: '100mg/ml'
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        maxDailyDose: 100,
        notes: 'Meningitis dose: 100mg/kg/day'
    },

    paracetamol: { // Enhanced from PDF
        name: 'Paracetamol',
        tradeNames: ['Ace', 'Napa', 'Renova'],
        category: 'Analgesic',
        forms: {
            syrup: {
                strength: '120mg/5ml',
                dosePerKg: 15,
                concentration: '24mg/ml'
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
        notes: 'Max 4g/day in adults'
    },

    albendazole: { // Enhanced from PDF
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
        notes: 'Take with fatty meal'
    },

    salbutamol: { // Enhanced from PDF
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
            }
        },
        weightBased: false,
        maxDosePerDay: 4,
        interval: '6-8',
        notes: 'Monitor heart rate'
    },

    phenobarbitone: {
        name: 'Phenobarbitone',
        tradeNames: ['Barbit', 'Epinal'],
        category: 'Anticonvulsant',
        forms: {
            injection: {
                strength: '200mg/ml',
                standardDose: {
                    'loading': '20mg/kg',
                    'maintenance': '2.5mg/kg'
                }
            },
            syrup: {
                strength: '20mg/5ml',
                standardDose: {
                    'maintenance': '2.5mg/kg'
                }
            }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        notes: 'Do not dilute injection'
    },
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
