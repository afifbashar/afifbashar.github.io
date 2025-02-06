const drugDatabase = {
    // Antibiotics
    ampicillin: {
        name: 'Ampicillin',
        tradeNames: ['Ampexin', 'Acmecillin', 'Ficillin', 'Pen-A'],
        category: 'Antibiotic',
        forms: {
            syrup: { strength: '125mg/5ml', dosePerKg: 100, concentration: '25mg/ml' },
            tablet: { strength: '250mg', dosePerKg: 100 },
            drops: { strength: '125mg/1.25ml', dosePerKg: 100, dropsPerMl: 15 },
            injection: { strength: '250mg/2.5ml', dosePerKg: 100 }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '8-12',
        notes: 'Neonates: 12 hourly; Infants and older: 8 hourly'
    },
    gentamycin: {
        name: 'Gentamycin',
        tradeNames: ['Gentin', 'Genacyn', 'Invigen'],
        category: 'Antibiotic',
        forms: {
            injection: { strength: '20mg/2ml', dosePerKg: 5 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8-24',
        notes: 'Neonates: Once daily; Infants and older: 8 hourly'
    },
    amikacin: {
        name: 'Amikacin',
        tradeNames: ['Kacin', 'Amibac', 'Amistar'],
        category: 'Antibiotic',
        forms: {
            injection: { strength: '100mg/2ml', dosePerKg: 7.5 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8-12',
        notes: 'Caution: Renal impairment'
    },
    ceftazidime: {
        name: 'Ceftazidime',
        tradeNames: ['Tazid', 'Zitum', 'Serozid'],
        category: 'Antibiotic',
        forms: {
            injection: { strength: '250mg/2.5ml', dosePerKg: 100 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        notes: 'For meningitis: 150mg/kg/day'
    },
    cefotaxime: {
        name: 'Cefotaxime',
        tradeNames: ['Maxcef', 'Cefotime', 'Taxim'],
        category: 'Antibiotic',
        forms: {
            injection: { strength: '250mg/2.5ml', dosePerKg: 100 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        notes: 'For meningitis: 150mg/kg/day'
    },
    ceftriaxone: {
        name: 'Ceftriaxone',
        tradeNames: [],
        category: 'Antibiotic',
        forms: {
            injection: { strength: '250mg/2.5ml', dosePerKg: 50 }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12-24',
        notes: 'Meningitis dose: 100mg/kg OD/BD'
    },
    meropenem: {
        name: 'Meropenem',
        tradeNames: ['Spacbac', 'I-Penem', 'Fulspec'],
        category: 'Antibiotic',
        forms: {
            injection: { strength: '500mg/10ml', dosePerKg: 60 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        notes: 'Always given after dilution with 10ml of IV fluid'
    },
    vancomycin: {
        name: 'Vancomycin',
        tradeNames: ['Vancomycin', 'Vanmycin', 'Vancomin'],
        category: 'Antibiotic',
        forms: {
            injection: { strength: '500mg/100ml', dosePerKg: 15 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8-12',
        notes: '<7 days: 12 hourly; >7 days: 8 hourly'
    },
    flucloxacillin: {
        name: 'Flucloxacillin',
        tradeNames: ['Fluclox', 'Phylopen', 'Phylopen Forte DS Flux'],
        category: 'Antibiotic',
        forms: {
            syrup: { strength: '125mg/5ml', dosePerKg: 50 },
            capsule: { strength: '250mg', dosePerKg: 50 }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6',
        notes: 'Infants: Oral; Adults: Injection'
    },
    amoxicillin: {
        name: 'Amoxicillin',
        tradeNames: ['Moxacil', 'Tycil', 'Fimoxyl'],
        category: 'Antibiotic',
        forms: {
            syrup: { strength: '125mg/5ml', dosePerKg: 40 },
            tablet: { strength: '250mg', dosePerKg: 40 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        notes: 'Complete full course as prescribed'
    },
    azithromycin: {
        name: 'Azithromycin',
        tradeNames: ['Zimax'],
        category: 'Antibiotic',
        forms: {
            syrup: { strength: '200mg/5ml', dosePerKg: 10 },
            tablet: { strength: '250mg', dosePerKg: 10 }
        },
        weightBased: true,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'Take on empty stomach'
    },
    cefixime: {
        name: 'Cefixime',
        tradeNames: ['Cef-3', 'Denvar', 'T-Cef'],
        category: 'Antibiotic',
        forms: {
            syrup: { strength: '100mg/5ml', dosePerKg: 10 },
            tablet: { strength: '200mg', dosePerKg: 10 }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        notes: 'Can be taken with or without food'
    },
    ciprofloxacin: {
        name: 'Ciprofloxacin',
        tradeNames: ['Ciprocin', 'Neofloxin', 'Flontin'],
        category: 'Antibiotic',
        forms: {
            syrup: { strength: '250mg/5ml', dosePerKg: 15 },
            tablet: { strength: '250mg', dosePerKg: 15 }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        notes: 'Avoid in children unless necessary'
    },
    metronidazole: {
        name: 'Metronidazole',
        tradeNames: ['Amodis', 'Flamyd', 'Flazyl'],
        category: 'Antibiotic',
        forms: {
            syrup: { strength: '200mg/5ml', dosePerKg: 20 },
            tablet: { strength: '400mg', dosePerKg: 20 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        notes: 'Take with food'
    },
    acyclovir: {
        name: 'Acyclovir',
        tradeNames: ['Zovirux', 'Xovir', 'Virux'],
        category: 'Antiviral',
        forms: {
            syrup: { strength: '200mg/5ml', dosePerKg: 10 },
            injection: { strength: '250mg', dosePerKg: 10 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        notes: 'Dilute before administration'
    },
    fluconazole: {
        name: 'Fluconazole',
        tradeNames: ['Flugal', 'Lucan-R', 'Nispore'],
        category: 'Antifungal',
        forms: {
            syrup: { strength: '50mg/5ml', dosePerKg: 6 },
            capsule: { strength: '150mg', dosePerKg: 6 }
        },
        weightBased: true,
        maxDosePerDay: 1,
        interval: '24',
        notes: 'Duration depends on infection type'
    },
    albendazole: {
        name: 'Albendazole',
        tradeNames: ['Alben', 'Almex', 'Sintel'],
        category: 'Anthelmintic',
        forms: {
            syrup: { strength: '200mg/5ml', standardDose: { '<2years': '200mg', '>2years': '400mg' } },
            tablet: { strength: '400mg' }
        },
        weightBased: false,
        maxDosePerDay: 1,
        interval: '24',
        duration: 'Single dose, may repeat after 2 weeks',
        notes: 'Take with fatty meal for better absorption'
    },
    paracetamol: {
        name: 'Paracetamol',
        tradeNames: ['Ace', 'Napa', 'Renova'],
        category: 'Antipyretic/Analgesic',
        forms: {
            syrup: { strength: '120mg/5ml', dosePerKg: 15, concentration: '24mg/ml' },
            tablet: { strength: '500mg', dosePerKg: 15 },
            drops: { strength: '80mg/ml', dosePerKg: 15, dropsPerMl: 15 },
            suppository: { strength: '125mg', dosePerKg: 15 }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '4-6',
        maxDailyDose: 60,
        notes: 'Do not exceed 4g per day in adults'
    },
    domperidone: {
        name: 'Domperidone',
        tradeNames: ['Domin', 'Omidon', 'Motigut'],
        category: 'Antiemetic',
        forms: {
            syrup: { strength: '5mg/5ml', dosePerKg: 0.4, concentration: '1mg/ml' },
            tablet: { strength: '10mg', dosePerKg: 0.4 },
            drops: { strength: '5mg/ml', dosePerKg: 0.4, dropsPerMl: 15 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '6-8',
        notes: 'Take before meals'
    },
    ondansetron: {
        name: 'Ondansetron',
        tradeNames: ['Emistat', 'Anset', 'Onaseron'],
        category: 'Antiemetic',
        forms: {
            syrup: { strength: '4mg/5ml', dosePerKg: 0.4, concentration: '0.8mg/ml' },
            tablet: { strength: '4mg', dosePerKg: 0.4 },
            injection: { strength: '8mg/4ml', dosePerKg: 0.4 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        notes: 'Can cause headache'
    },
    diazepam: {
        name: 'Diazepam',
        tradeNames: ['Sedil', 'Easium'],
        category: 'Anticonvulsant',
        forms: {
            syrup: { strength: '5mg/5ml', dosePerKg: 0.5 },
            injection: { strength: '10mg/2ml', dosePerKg: 0.3 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '8',
        notes: 'Administer with caution'
    },
    phenobarbitone: {
        name: 'Phenobarbitone',
        tradeNames: ['Barbit', 'Epinal'],
        category: 'Anticonvulsant',
        forms: {
            syrup: { strength: '20mg/5ml', dosePerKg: 20 },
            tablet: { strength: '30mg', dosePerKg: 20 }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12',
        notes: 'Do not dilute (oily preparation)'
    },
    midazolam: {
        name: 'Midazolam',
        tradeNames: ['Dormicum', 'Hypnofast'],
        category: 'Anticonvulsant',
        forms: {
            injection: { strength: '5mg/ml', dosePerKg: 0.1 }
        },
        weightBased: true,
        maxDosePerDay: 1,
        interval: '6-8',
        notes: 'Use for status epilepticus'
    },
    salbutamol: {
        name: 'Salbutamol',
        tradeNames: ['Sultolin', 'Brodil', 'Ventolin'],
        category: 'Bronchodilator',
        forms: {
            syrup: { strength: '2mg/5ml', dosePerKg: 0.2 },
            nebulization: { strength: '5mg/ml', dosePerKg: 0.04 }
        },
        weightBased: true,
        maxDosePerDay: 4,
        interval: '6-8',
        notes: 'Monitor heart rate during nebulization'
    },
    ibuprofen: {
        name: 'Ibuprofen',
        tradeNames: ['Esrufen', 'Flamex', 'Inflam'],
        category: 'NSAID',
        forms: {
            syrup: { strength: '100mg/5ml', dosePerKg: 10 },
            tablet: { strength: '200mg', dosePerKg: 10 }
        },
        weightBased: true,
        maxDosePerDay: 3,
        interval: '6-8',
        notes: 'Take with food. Avoid if asthmatic'
    },
    prednisolone: {
        name: 'Prednisolone',
        tradeNames: ['Cortan', 'Precodil', 'Deltasone'],
        category: 'Corticosteroid',
        forms: {
            syrup: { strength: '5mg/5ml', dosePerKg: 1 },
            tablet: { strength: '5mg', dosePerKg: 1 }
        },
        weightBased: true,
        maxDosePerDay: 2,
        interval: '12-24',
        notes: 'Take with food. Do not stop abruptly'
    }
};

// Function to calculate dose
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
                warningText = `Warning: Calculated daily dose exceeds maximum recommended dose of ${med.maxDailyDose}mg/kg/day`;
            }
        }

        switch (form) {
            case 'syrup':
                const mlDose = (totalDose * 5) / parseInt(formInfo.strength.split('mg/')[0]);
                const tspDose = mlDose / 5;
                doseText = `
                    Single Dose: ${totalDose.toFixed(1)}mg (${mlDose.toFixed(1)}ml or ${tspDose.toFixed(1)} teaspoons)
                    Given every ${med.interval} hours
                    Strength: ${formInfo.strength}
                    Daily Dose: ${(totalDose * med.maxDosePerDay).toFixed(1)}mg
                `;
                break;

            case 'drops':
                const dropDose = (totalDose / parseInt(formInfo.strength)) * formInfo.dropsPerMl;
                doseText = `
                    Single Dose: ${totalDose.toFixed(1)}mg (${dropDose.toFixed(0)} drops)
                    Given every ${med.interval} hours
                    Strength: ${formInfo.strength}
                `;
                break;

            default:
                doseText = `Single Dose: ${totalDose.toFixed(1)}mg`;
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
            Standard Dose: ${dosage[ageGroup]}
            Given every ${med.interval} hours
            Maximum ${med.maxDosePerDay} doses per day
        `;
    }

    return `
        ${med.name} Dosing
        ${doseText}
        ${med.notes ? `Note: ${med.notes}` : ''}
        ${warningText}
    `;
}

// Update form options when medication changes
document.getElementById('medication').addEventListener('change', function (e) {
    const medication = e.target.value;
    const formSelect = document.getElementById('medicationForm');
    formSelect.innerHTML = '<option>Select form</option>';
    if (medication && drugDatabase[medication]) {
        Object.keys(drugDatabase[medication].forms).forEach(form => {
            const option = document.createElement('option');
            option.value = form;
            option.textContent = form.charAt(0).toUpperCase() + form.slice(1);
            formSelect.appendChild(option);
        });
        formSelect.disabled = false;
    } else {
        formSelect.disabled = true;
    }
});

// Search functionality
document.getElementById('drugSearch').addEventListener('input', function () {
    const query = this.value.toLowerCase().trim();
    const searchResults = document.getElementById('searchResults');
    searchResults.innerHTML = ''; // Clear previous results

    if (query.length > 0) {
        const results = searchDrug(query);
        if (results.length > 0) {
            results.forEach(drug => {
                const resultItem = document.createElement('div');
                resultItem.className = 'list-group-item';
                resultItem.textContent = `${drug.name} (${drug.tradeNames.join(', ')})`;
                resultItem.addEventListener('click', function () {
                    populateMedicationDropdown(drug.key);
                    populateFormDropdown(drug.key);
                    document.getElementById('drugSearch').value = '';
                    searchResults.style.display = 'none';
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

// Populate dropdowns
function populateMedicationDropdown(selectedKey) {
    const medicationSelect = document.getElementById('medication');
    medicationSelect.innerHTML = '<option>Select medication</option>';
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
    const formSelect = document.getElementById('medicationForm');
    formSelect.innerHTML = '<option>Select form</option>';
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

// Enhanced search function
function searchDrug(query) {
    query = query.toLowerCase();
    return Object.entries(drugDatabase)
        .filter(([_, drug]) => drug.name.toLowerCase().includes(query) || drug.tradeNames.some(name => name.toLowerCase().includes(query)))
        .map(([key, drug]) => ({ key: key, name: drug.name, tradeNames: drug.tradeNames }));
}
