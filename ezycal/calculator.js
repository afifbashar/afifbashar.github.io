const drugDatabase = {
    // Existing drugs remain unchanged
    // ... 
    
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
