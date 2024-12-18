// MVP Provider Data
const networkProviders = {
    telstra: {
        name: "Telstra",
        type: "major",
        coverage: {
            sydney: {
                general: "Excellent",
                cbd: { general: "Excellent" },
                inner: { general: "Excellent" },
                eastern: { general: "Excellent" },
                western: { general: "Very Good" },
                northern: { general: "Excellent" },
                southern: { general: "Very Good" },
                dead_zones: [
                    {
                        location: "Train Tunnels",
                        description: "Limited coverage in underground sections"
                    }
                ],
                peak_performance: {
                    cbd: {
                        peak_times: "8:00-9:30AM, 5:00-6:30PM",
                        impact: "May experience slower speeds during peak hours"
                    }
                }
            }
        },
        plans: {
            prepaid: [
                {
                    name: "Tourist Starter",
                    duration: 28,
                    data: "30GB",
                    price: 30,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 15 countries",
                        "4G network access"
                    ]
                },
                {
                    name: "Tourist Plus",
                    duration: 28,
                    data: "65GB",
                    price: 45,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 20 countries",
                        "5G network access"
                    ]
                },
                {
                    name: "Student Starter",
                    duration: 28,
                    data: "40GB",
                    price: 30,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 15 countries",
                        "Student bonus data"
                    ]
                },
                {
                    name: "Student Plus",
                    duration: 28,
                    data: "65GB",
                    price: 45,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 20 countries",
                        "Student bonus data",
                        "Entertainment package included"
                    ]
                }
            ],
            postpaid: [
                {
                    name: "Business Essential",
                    duration: 30,
                    data: "120GB",
                    price: 65,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 20 countries",
                        "5G network access",
                        "Priority customer support",
                        "Business account manager",
                        "Static IP option"
                    ]
                },
                {
                    name: "Business Premium",
                    duration: 30,
                    data: "300GB",
                    price: 85,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 35 countries",
                        "5G network access",
                        "Priority customer support",
                        "Business account manager",
                        "Microsoft 365 Business Basic included",
                        "Data sharing across plans"
                    ]
                },
                {
                    name: "Resident Value",
                    duration: 30,
                    data: "80GB",
                    price: 55,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 15 countries",
                        "5G network access",
                        "Data banking up to 500GB"
                    ]
                },
                {
                    name: "Resident Premium",
                    duration: 30,
                    data: "240GB",
                    price: 75,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 30 countries",
                        "5G network access",
                        "Data banking up to 1TB",
                        "Entertainment package included"
                    ]
                }
            ]
        },
        store_locations: {
            cbd: [
                {
                    name: "Telstra Store Sydney CBD",
                    address: "400 George Street, Sydney",
                    hours: "Mon-Sat: 9:00-17:30",
                    phone: "02 9XXX XXXX",
                    google_maps: "https://maps.google.com",
                    nearest_transport: "Town Hall Station (2 min walk)"
                }
            ]
        },
        support: {
            tourist_hotline: "1800 XXX XXX",
            tourist_support_hours: "24/7",
            languages: ["English", "Mandarin", "Korean"]
        },
        business_support: {
            hotline: "1800 XXX XXX",
            support_hours: "24/7",
            features: [
                "Dedicated business account manager",
                "Priority technical support",
                "Online business portal"
            ]
        },
        resident_support: {
            hotline: "1800 XXX XXX",
            support_hours: "24/7",
            features: [
                "24/7 technical support",
                "Online account management",
                "Local store support"
            ]
        }
    },
    optus: {
        name: "Optus",
        type: "major",
        coverage: {
            sydney: {
                general: "Excellent",
                cbd: { general: "Excellent" },
                inner: { general: "Very Good" },
                eastern: { general: "Excellent" },
                western: { general: "Very Good" },
                northern: { general: "Very Good" },
                southern: { general: "Very Good" }
            }
        },
        plans: {
            prepaid: [
                {
                    name: "Visitor Plan",
                    duration: 28,
                    data: "25GB",
                    price: 25,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 10 countries",
                        "4G network access"
                    ]
                },
                {
                    name: "Tourist Max",
                    duration: 28,
                    data: "55GB",
                    price: 40,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 15 countries",
                        "5G network access"
                    ]
                }
            ],
            postpaid: [
                {
                    name: "Business Starter",
                    duration: 30,
                    data: "100GB",
                    price: 60,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 15 countries",
                        "5G network access",
                        "Business support line",
                        "Fleet management portal"
                    ]
                },
                {
                    name: "Business Plus",
                    duration: 30,
                    data: "250GB",
                    price: 80,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 30 countries",
                        "5G network access",
                        "Priority business support",
                        "Fleet management portal",
                        "Data pooling available"
                    ]
                },
                {
                    name: "Resident Basic",
                    duration: 30,
                    data: "75GB",
                    price: 50,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 10 countries",
                        "5G network access",
                        "Data rollover"
                    ]
                },
                {
                    name: "Resident Max",
                    duration: 30,
                    data: "200GB",
                    price: 70,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 25 countries",
                        "5G network access",
                        "Data rollover",
                        "Entertainment package included"
                    ]
                }
            ]
        },
        store_locations: {
            cbd: [
                {
                    name: "Optus Store Pitt St",
                    address: "Pitt Street Mall, Sydney",
                    hours: "Mon-Sat: 9:00-17:30",
                    phone: "02 9XXX XXXX",
                    google_maps: "https://maps.google.com",
                    nearest_transport: "Town Hall Station (5 min walk)"
                }
            ]
        },
        support: {
            tourist_hotline: "1800 XXX XXX",
            tourist_support_hours: "8AM-8PM",
            languages: ["English", "Mandarin"]
        },
        business_support: {
            hotline: "1800 XXX XXX",
            support_hours: "8AM-8PM",
            features: [
                "Business support team",
                "Online business portal",
                "Fleet management tools"
            ]
        },
        resident_support: {
            hotline: "1800 XXX XXX",
            support_hours: "8AM-8PM",
            features: [
                "Local technical support",
                "Online self-service",
                "Store support network"
            ]
        }
    }
};

const mvnoProviders = {
    boost: {
        name: "Boost Mobile",
        type: "mvno",
        network: "telstra",
        coverage: {
            sydney: {
                general: "Very Good",
                cbd: { general: "Very Good" },
                inner: { general: "Very Good" },
                eastern: { general: "Very Good" },
                western: { general: "Good" },
                northern: { general: "Very Good" },
                southern: { general: "Good" }
            }
        },
        plans: {
            prepaid: [
                {
                    name: "Tourist Value",
                    duration: 28,
                    data: "20GB",
                    price: 20,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 5 countries",
                        "4G network access"
                    ]
                },
                {
                    name: "Tourist Pro",
                    duration: 28,
                    data: "45GB",
                    price: 35,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 10 countries",
                        "4G network access"
                    ]
                }
            ],
            postpaid: [
                {
                    name: "Resident Essential",
                    duration: 30,
                    data: "65GB",
                    price: 45,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 10 countries",
                        "4G network access",
                        "Data banking"
                    ]
                },
                {
                    name: "Resident Plus",
                    duration: 30,
                    data: "185GB",
                    price: 65,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 20 countries",
                        "4G network access",
                        "Data banking",
                        "Entertainment extras"
                    ]
                }
            ]
        },
        store_locations: {
            cbd: [
                {
                    name: "Boost Kiosk World Square",
                    address: "World Square Shopping Centre",
                    hours: "Mon-Sun: 10:00-18:00",
                    phone: "02 9XXX XXXX",
                    google_maps: "https://maps.google.com",
                    nearest_transport: "Town Hall Station (7 min walk)"
                }
            ]
        },
        support: {
            tourist_hotline: "1800 XXX XXX",
            tourist_support_hours: "9AM-6PM",
            languages: ["English"]
        },
        resident_support: {
            hotline: "1800 XXX XXX",
            support_hours: "9AM-6PM",
            features: [
                "Phone support",
                "Online chat support",
                "Store support"
            ]
        }
    }
};

// Functions used by the plan finder
function getDetailedRecommendations(userProfile) {
    console.log('Getting recommendations for:', userProfile);

    try {
        // Input validation
        if (!userProfile || !userProfile.location || !userProfile.budget) {
            throw new Error('Missing required profile information');
        }

        let allProviders = [
            ...Object.values(networkProviders),
            ...Object.values(mvnoProviders)
        ];

        // Score and filter providers
        const scoredProviders = allProviders.map(provider => {
            let score = 0;
            const reasons = [];

            // Score based on user type
            if (userProfile.userType === 'student') {
                const hasStudentPlans = provider.plans?.prepaid?.some(
                    plan => plan.features.some(f => f.toLowerCase().includes('student'))
                );
                if (hasStudentPlans) {
                    score += 20;
                    reasons.push('Student-specific plans available');
                }
            }

            // Score coverage
            const coverage = provider.coverage?.sydney?.[userProfile.location]?.general;
            if (coverage) {
                switch (coverage.toLowerCase()) {
                    case 'excellent':
                        score += 30;
                        reasons.push('Excellent coverage in your area');
                        break;
                    case 'very good':
                        score += 25;
                        reasons.push('Very good coverage in your area');
                        break;
                    case 'good':
                        score += 20;
                        reasons.push('Good coverage in your area');
                        break;
                }
            }

            // Score budget match
            const plans = provider.plans?.prepaid || [];
            const hasPlanInBudget = plans.some(plan =>
                plan.price <= parseInt(userProfile.budget)
            );

            if (hasPlanInBudget) {
                score += 20;
                reasons.push('Has plans within your budget');
            }

            // Score data needs
            const hasEnoughData = plans.some(plan => {
                const planData = parseInt(plan.data) || 0;
                switch (userProfile.dataNeeds) {
                    case 'light': return planData >= 15;
                    case 'medium': return planData >= 30;
                    case 'heavy': return planData >= 50;
                    default: return true;
                }
            });

            if (hasEnoughData) {
                score += 20;
                reasons.push('Matches your data needs');
            }

            // Score features
            if (userProfile.needs?.international) {
                const hasInternational = plans.some(plan =>
                    plan.features.some(f => f.toLowerCase().includes('international'))
                );
                if (hasInternational) {
                    score += 10;
                    reasons.push('International calling available');
                }
            }

            return {
                ...provider,
                score,
                reasons,
                isGoodMatch: score > 40
            };
        });

        // Filter and sort recommendations
        const recommendations = scoredProviders
            .filter(provider => provider.isGoodMatch)
            .sort((a, b) => b.score - a.score)
            .slice(0, 3);

        if (recommendations.length === 0) {
            throw new Error('No matching plans found. Try adjusting your criteria.');
        }

        return recommendations;

    } catch (error) {
        console.error('Error in recommendations:', error);
        throw error;
    }
}
// Helper functions used by the UI
function findBestMatchingPlan(provider, userProfile) {
    const plans = provider.plans?.prepaid || [];
    const budget = parseInt(userProfile.budget);

    return plans.reduce((best, plan) => {
        if (!best) return plan;
        if (plan.price <= budget && (!best || plan.price > best.price)) {
            return plan;
        }
        return best;
    }, null);
}

function findCheapestPlan(provider) {
    const plans = provider.plans?.prepaid || [];
    return plans.reduce((cheapest, plan) => {
        if (!cheapest || plan.price < cheapest.price) return plan;
        return cheapest;
    }, null);
}

// Export for browser use
window.networkProviders = networkProviders;
window.mvnoProviders = mvnoProviders;
window.getDetailedRecommendations = getDetailedRecommendations;
window.findBestMatchingPlan = findBestMatchingPlan;
window.findCheapestPlan = findCheapestPlan;