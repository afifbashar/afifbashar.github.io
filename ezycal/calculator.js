const drugDatabase = {
    // Existing drugs remain unchanged
    // ... 
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

    // Add more drugs following the same pattern...
};
