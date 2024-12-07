// Network Providers (Major Carriers)
const networkProviders = {
    telstra: {
        name: "Telstra",
        type: "major",
        coverage: {
            sydney: {
                general: "Excellent",
                cbd: "Excellent",
                suburban: "Excellent",
                regional: "Excellent"
            }
        },
        features: ["5G", "4G", "3G"],
        speed: "Fastest available",
        networkTier: "Premium"
    },
    optus: {
        name: "Optus",
        type: "major",
        coverage: {
            sydney: {
                general: "Excellent",
                cbd: "Excellent",
                suburban: "Very Good",
                regional: "Good"
            }
        },
        features: ["5G", "4G", "3G"],
        speed: "Fast",
        networkTier: "Premium"
    },
    vodafone: {
        name: "Vodafone",
        type: "major",
        coverage: {
            sydney: {
                general: "Very Good",
                cbd: "Excellent",
                suburban: "Good",
                regional: "Fair"
            }
        },
        features: ["5G", "4G", "3G"],
        speed: "Fast",
        networkTier: "Standard"
    }
};

// All Providers with their details
const providers = [
    {
        name: "Telstra",
        type: "major",
        network: "telstra",
        plans: {
            prepaid: [
                {
                    name: "Prepaid Max",
                    data: "Unlimited",
                    price: 65,
                    features: ["5G Access", "Unlimited Calls/Text", "International Minutes"]
                }
            ],
            postpaid: [
                {
                    name: "Premium Mobile",
                    data: "180GB",
                    price: 85,
                    features: ["5G Access", "International Roaming", "Entertainment Extras"]
                }
            ]
        },
        specialFeatures: ["5G Network", "Telstra Air WiFi", "Entertainment Extras", "International Roaming"],
        bestFor: ["Premium Users", "Business", "High Data Users"],
        storeLocations: {
            cbd: ["Pitt St Mall", "World Square"],
            suburban: ["Bondi Junction", "Parramatta"]
        }
    },
    // Add more providers here...
    // Major Carriers (continue after Telstra)
    {
        name: "Optus",
        type: "major",
        network: "optus",
        plans: {
            prepaid: [
                {
                    name: "Prepaid Plus",
                    data: "100GB",
                    price: 49,
                    features: ["4G/5G", "Unlimited Calls/Text", "Entertainment Extras"]
                },
                {
                    name: "Prepaid Basic",
                    data: "40GB",
                    price: 30,
                    features: ["4G Coverage", "Unlimited Calls/Text"]
                }
            ],
            postpaid: [
                {
                    name: "Optus Choice Plus",
                    data: "150GB",
                    price: 65,
                    features: ["5G Access", "International Calls", "Entertainment Bundle"]
                },
                {
                    name: "Optus Premium",
                    data: "Unlimited",
                    price: 85,
                    features: ["5G Access", "International Roaming", "Entertainment Bundle"]
                }
            ]
        },
        specialFeatures: ["5G Network", "Optus Sport", "Entertainment Bundle", "International Calls"],
        bestFor: ["Entertainment", "Value Seekers", "Students"],
        storeLocations: {
            cbd: ["George Street", "Broadway Shopping Centre"],
            suburban: ["Chatswood", "Miranda"]
        }
    },
    {
        name: "Vodafone",
        type: "major",
        network: "vodafone",
        plans: {
            prepaid: [
                {
                    name: "Prepaid Plus",
                    data: "65GB",
                    price: 40,
                    features: ["5G Access", "International Minutes", "Data Banking"]
                },
                {
                    name: "Prepaid Starter",
                    data: "25GB",
                    price: 25,
                    features: ["4G Coverage", "Unlimited Calls/Text"]
                }
            ],
            postpaid: [
                {
                    name: "5G Infinite",
                    data: "Unlimited",
                    price: 65,
                    features: ["5G Access", "International Calls", "Bundle Discounts"]
                },
                {
                    name: "Premium Infinite",
                    data: "Unlimited",
                    price: 85,
                    features: ["5G Access", "International Roaming", "Bundle Discounts"]
                }
            ]
        },
        specialFeatures: ["5G Network", "International Roaming", "Bundle Discounts"],
        bestFor: ["International Users", "High Data Users", "Students"],
        storeLocations: {
            cbd: ["QVB", "World Square"],
            suburban: ["Parramatta", "Bondi Junction"]
        }
    },

    // MVNOs Using Telstra's Network
    {
        name: "Boost Mobile",
        type: "mvno",
        network: "telstra",
        plans: {
            prepaid: [
                {
                    name: "Premium Prepaid",
                    data: "100GB",
                    price: 50,
                    features: ["Full Telstra Coverage", "International Calls", "Data Banking"]
                },
                {
                    name: "Basic Prepaid",
                    data: "40GB",
                    price: 30,
                    features: ["Full Telstra Coverage", "Unlimited Calls/Text"]
                }
            ]
        },
        specialFeatures: ["Data Banking", "Full Telstra Coverage", "International Calls"],
        bestFor: ["Value Seekers", "Students", "Light Users"]
    },
    {
        name: "ALDI Mobile",
        type: "mvno",
        network: "telstra",
        plans: {
            prepaid: [
                {
                    name: "XL Value Pack",
                    data: "65GB",
                    price: 45,
                    features: ["Telstra Network", "Unlimited Calls/Text", "Data Banking"]
                },
                {
                    name: "M Value Pack",
                    data: "25GB",
                    price: 25,
                    features: ["Telstra Network", "Unlimited Calls/Text"]
                }
            ]
        },
        specialFeatures: ["Data Banking", "Value Plans"],
        bestFor: ["Budget Conscious", "Light Users"]
    },
    {
        name: "Belong",
        type: "mvno",
        network: "telstra",
        plans: {
            postpaid: [
                {
                    name: "Large Mobile Plan",
                    data: "100GB",
                    price: 45,
                    features: ["Data Banking", "Unlimited Calls/Text", "Data Gifting"]
                },
                {
                    name: "Regular Mobile Plan",
                    data: "40GB",
                    price: 25,
                    features: ["Data Banking", "Unlimited Calls/Text"]
                }
            ]
        },
        specialFeatures: ["Data Banking", "Data Gifting", "Carbon Neutral"],
        bestFor: ["Value Seekers", "Environmentally Conscious"]
    },

    // MVNOs Using Optus Network
    {
        name: "Amaysim",
        type: "mvno",
        network: "optus",
        plans: {
            prepaid: [
                {
                    name: "Unlimited 80GB",
                    data: "80GB",
                    price: 40,
                    features: ["Optus 4G", "Unlimited Calls/Text", "International Calls"]
                },
                {
                    name: "Unlimited 30GB",
                    data: "30GB",
                    price: 25,
                    features: ["Optus 4G", "Unlimited Calls/Text"]
                }
            ]
        },
        specialFeatures: ["No Lock-in Contracts", "International Calls"],
        bestFor: ["Value Seekers", "International Callers"]
    },
    {
        name: "Circles.Life",
        type: "mvno",
        network: "optus",
        plans: {
            postpaid: [
                {
                    name: "100GB Plan",
                    data: "100GB",
                    price: 35,
                    features: ["Optus 4G/5G", "Unlimited Calls/Text", "Data Banking"]
                },
                {
                    name: "50GB Plan",
                    data: "50GB",
                    price: 25,
                    features: ["Optus 4G", "Unlimited Calls/Text"]
                }
            ]
        },
        specialFeatures: ["Data Banking", "Flexible Plans", "Bill Shock Protection"],
        bestFor: ["Digital Natives", "Value Seekers"]
    },

    // MVNOs Using Vodafone Network
    {
        name: "Lebara",
        type: "mvno",
        network: "vodafone",
        plans: {
            prepaid: [
                {
                    name: "Extra Large Plan",
                    data: "80GB",
                    price: 45,
                    features: ["Vodafone 4G", "International Calls", "Data Banking"]
                },
                {
                    name: "Medium Plan",
                    data: "30GB",
                    price: 25,
                    features: ["Vodafone 4G", "International Calls"]
                }
            ]
        },
        specialFeatures: ["International Calls", "Data Banking"],
        bestFor: ["International Callers", "Tourists"]
    },
    {
        name: "TPG",
        type: "mvno",
        network: "vodafone",
        plans: {
            postpaid: [
                {
                    name: "Large Plan",
                    data: "60GB",
                    price: 40,
                    features: ["Vodafone 4G", "Unlimited Calls/Text", "No Lock-in"]
                },
                {
                    name: "Medium Plan",
                    data: "30GB",
                    price: 30,
                    features: ["Vodafone 4G", "Unlimited Calls/Text"]
                }
            ]
        },
        specialFeatures: ["No Lock-in Contracts", "Bundle with Internet"],
        bestFor: ["Value Seekers", "Bundle Customers"]
    }
];

// Special Deals and Promotions
const specialDeals = {
    entertainment: {
        sports: ["Telstra", "Optus"],
        streaming: ["Vodafone", "Optus", "Telstra"]
    },
    international: {
        roaming: ["Telstra", "Optus", "Vodafone"],
        calls: ["Lycamobile", "Lebara", "Vodafone"]
    },
    student: {
        providers: ["Optus", "Vodafone", "Boost Mobile"],
        discounts: true
    }
};

// Usage Scenarios for Different User Types
const localInsights = {
    usage_scenarios: {
        tourist: {
            recommended_duration: ["short", "medium"],
            best_providers: ["Boost Mobile", "Amaysim", "Lycamobile"],
            important_features: ["prepaid", "international_calls", "flexible_duration"],
            avg_data_needs: "30GB",
            typical_budget: "30-50"
        },
        student: {
            recommended_duration: ["long", "permanent"],
            best_providers: ["Optus", "Vodafone", "Boost Mobile"],
            important_features: ["data_heavy", "entertainment", "student_discount"],
            avg_data_needs: "100GB",
            typical_budget: "30-65"
        },
        business: {
            recommended_duration: ["permanent"],
            best_providers: ["Telstra", "Optus", "Vodafone"],
            important_features: ["reliability", "coverage", "5g", "support"],
            avg_data_needs: "Unlimited",
            typical_budget: "65-120"
        },
        resident: {
            recommended_duration: ["permanent"],
            best_providers: ["Belong", "Boost Mobile", "Amaysim"],
            important_features: ["value", "local_calls", "flexible_data"],
            avg_data_needs: "50GB",
            typical_budget: "25-45"
        }
    },
    location_insights: {
        cbd: {
            best_coverage: ["Telstra", "Optus", "Vodafone"],
            peak_hours: "8am-6pm",
            typical_speeds: "Excellent"
        },
        inner: {
            best_coverage: ["Telstra", "Optus", "Vodafone"],
            peak_hours: "7am-7pm",
            typical_speeds: "Very Good"
        },
        eastern: {
            best_coverage: ["Telstra", "Optus"],
            peak_hours: "6am-10pm",
            typical_speeds: "Very Good"
        },
        western: {
            best_coverage: ["Telstra", "Optus"],
            peak_hours: "6am-8pm",
            typical_speeds: "Good"
        }
    }
};

// Function to get recommendations based on user profile
function getRecommendations(userProfile) {
    let matchedProviders = [...providers];
    const userInsights = localInsights.usage_scenarios[userProfile.userType];

    // Filter by budget
    if (userProfile.budget) {
        const maxBudget = parseInt(userProfile.budget);
        matchedProviders = matchedProviders.filter(provider => {
            const cheapestPlan = Math.min(
                ...Object.values(provider.plans)
                    .flat()
                    .map(plan => plan.price)
            );
            return cheapestPlan <= maxBudget;
        });
    }

    // Filter by plan type
    if (userProfile.planType !== 'any') {
        matchedProviders = matchedProviders.filter(provider =>
            provider.plans[userProfile.planType]?.length > 0
        );
    }

    // Score providers based on user needs
    matchedProviders = matchedProviders.map(provider => {
        let score = 0;
        const network = networkProviders[provider.network];

        // Score based on coverage
        if (userProfile.needs.coverage) {
            score += network.coverage.sydney.general === 'Excellent' ? 3 :
                network.coverage.sydney.general === 'Very good' ? 2 : 1;
        }

        // Score based on user type match
        if (userInsights?.best_providers.includes(provider.name)) {
            score += 2;
        }

        // Score based on features
        if (userProfile.needs.international &&
            provider.specialFeatures?.some(f => f.toLowerCase().includes('international'))) {
            score += 2;
        }

        if (userProfile.needs.entertainment &&
            specialDeals.entertainment.streaming.includes(provider.name)) {
            score += 1;
        }

        return {
            ...provider,
            score,
            network: network
        };
    });

    // Sort by score
    return matchedProviders.sort((a, b) => b.score - a.score);
}

// Add this function before the final window assignments
function getPlanInsights(plan, userProfile) {
    const insights = [];

    // Network Quality Insights
    if (plan.network.name === "Telstra") {
        insights.push({
            type: "benefit",
            icon: "✓",
            message: "Best network coverage in Sydney - ideal for reliable connectivity"
        });

        if (userProfile.location === "western" || userProfile.location === "southern") {
            insights.push({
                type: "benefit",
                icon: "✓",
                message: "Superior coverage in outer Sydney areas"
            });
        }
    }

    // Data and Plan Type Insights
    if (userProfile.dataNeeds === "heavy") {
        const planType = userProfile.planType === 'any' ?
            (plan.plans.postpaid ? 'postpaid' : 'prepaid') :
            userProfile.planType;

        const selectedPlan = plan.plans[planType][0];

        if (selectedPlan.data !== "Unlimited") {
            insights.push({
                type: "warning",
                icon: "⚠",
                message: "For heavy data users, consider unlimited plans to avoid excess charges"
            });
        }
    }

    // Provider-specific insights
    switch (plan.name) {
        case "Optus":
            insights.push({
                type: "tip",
                icon: "💡",
                message: "CBA customers get up to 20% off through CommBank Rewards"
            });
            break;

        case "Telstra":
            if (plan.specialFeatures.includes("Entertainment Extras")) {
                insights.push({
                    type: "tip",
                    icon: "💡",
                    message: "Includes Foxtel Now + free sports streaming"
                });
            }
            break;
    }

    // Stay duration insights
    if (userProfile.stayDuration === "short" && !plan.name.toLowerCase().includes("prepaid")) {
        insights.push({
            type: "warning",
            icon: "⚠",
            message: "Short-term visitors should consider prepaid plans"
        });
    }

    return insights;
}

// Add this line with the other window assignments at the bottom
window.getPlanInsights = getPlanInsights;

// Make these available to other files
window.networkProviders = networkProviders;
window.providers = providers;
window.specialDeals = specialDeals;
window.localInsights = localInsights;
window.getRecommendations = getRecommendations;