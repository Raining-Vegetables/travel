// Enhanced Network Providers Structure
const networkProviders = {
    telstra: {
        name: "Telstra",
        type: "major",
        coverage: {
            sydney: {
                general: "Excellent",
                cbd: "Excellent",
                suburban: "Excellent",
                regional: "Excellent",
                // Added detailed coverage information
                dead_zones: [
                    {
                        location: "Central Station Underground",
                        description: "Limited coverage in underground platforms",
                        alternative: "Free Telstra WiFi available"
                    },
                    // Add more dead zones
                ],
                peak_performance: {
                    cbd: {
                        peak_times: "8:00-9:30AM, 5:00-6:30PM",
                        impact: "20-30% slower data speeds",
                        alternatives: "Telstra Air WiFi hotspots"
                    },
                    bondi: {
                        peak_times: "10:00AM-4:00PM weekends",
                        impact: "30-40% slower during peak beach hours",
                        alternatives: "Connect to Bondi Pavilion free WiFi"
                    }
                    // Add more areas
                }
            }
        },

        // 5G Information
        network_technology: {
            "5G": {
                availability: {
                    cbd: "95% coverage",
                    inner: "85% coverage",
                    eastern: "80% coverage",
                    // Add more areas
                },
                battery_impact: "20-30% faster battery drain on 5G",
                speed_benefit: "Up to 20x faster than 4G in covered areas",
                optimization_tips: [
                    "Switch to 4G when battery is below 30%",
                    "Use 5G auto-switch feature for balance"
                ]
            }
        },
        // Add plans
        plans: {
            prepaid: [
                {
                    name: "Tourist Plan Basic",
                    duration: 28,
                    data: "30GB",
                    price: 30,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 15 countries",
                        "5G network access"
                    ]
                },
                {
                    name: "Tourist Plan Plus",
                    duration: 28,
                    data: "65GB",
                    price: 40,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 20 countries",
                        "5G network access",
                        "Entertainment add-ons available"
                    ]
                },
                {
                    name: "Tourist Plan Premium",
                    duration: 28,
                    data: "100GB",
                    price: 60,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 30 countries",
                        "5G network access",
                        "Entertainment package included"
                    ]
                }
            ],
            postpaid: [
                {
                    name: "Essential Plan",
                    duration: 30,
                    data: "40GB",
                    price: 45,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "5G network access"
                    ]
                },
                {
                    name: "Premium Plan",
                    duration: 30,
                    data: "180GB",
                    price: 65,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 20 countries",
                        "5G network access",
                        "Entertainment package included"
                    ]
                },
                {
                    name: "Ultimate Plan",
                    duration: 30,
                    data: "300GB",
                    price: 85,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to all countries",
                        "5G network access",
                        "Entertainment package included",
                        "International roaming included"
                    ]
                }
            ]
        },
        // Store Locations and Support
        store_locations: {
            cbd: [
                {
                    name: "Telstra Store Sydney",
                    address: "Shop 2, 400 George Street, Sydney NSW 2000",
                    hours: "Mon-Sat: 9:00-17:30, Sun: 10:00-17:00",
                    phone: "02 9376 6400",
                    specialists: ["Tourist Plans", "International Services"],
                    google_maps: "https://goo.gl/maps/5PABCqj4gJ9kp8jZ6",
                    nearest_transport: "Town Hall Station (2 min walk)"
                },
                {
                    name: "Telstra Store World Square",
                    address: "Shop 9.28, World Square, 644 George St, Sydney NSW 2000",
                    hours: "Mon-Sat: 9:00-17:30, Sun: 10:00-17:00",
                    phone: "02 9268 1300",
                    specialists: ["International Services"],
                    google_maps: "https://goo.gl/maps/1Mh7gQJKPgv8RKZH6",
                    nearest_transport: "Town Hall Station (5 min walk)"
                }
            ],
            bondi: [
                {
                    name: "Telstra Store Bondi Junction",
                    address: "Shop 2037, Westfield Bondi Junction, 500 Oxford St",
                    hours: "Mon-Sat: 9:00-17:30, Sun: 10:00-17:00",
                    phone: "02 9387 2300",
                    specialists: ["Tourist Plans"],
                    google_maps: "https://goo.gl/maps/QXH4Q4Z1yqJ2",
                    nearest_transport: "Bondi Junction Station (connected)"
                }
            ]
        },

        // Add more areas

        // Public WiFi Complementary Service
        complementary_wifi: {
            name: "Telstra Air",
            locations: {
                cbd: ["QVB", "Pitt St Mall", "Circular Quay"],
                bondi: ["Bondi Beach", "Bondi Junction"],
                // Add more areas
            },
            access: "Free with all Telstra plans",
            login_method: "Automatic with Telstra SIM"
        },
        // Cost-Saving Partnerships
        partnerships: [
            {
                partner: "Flybuys",
                benefit: "1 point per $1 spent",
                how_to_activate: "Link your Flybuys card in Telstra app"
            },
            {
                partner: "AFL Live Pass",
                benefit: "Free streaming of all AFL games",
                how_to_activate: "Download AFL app and verify Telstra number"
            }
            // Add more partnerships
        ],
        // Support Contacts
        support: {
            tourist_hotline: "+61 2 XXXX XXXX",
            tourist_support_hours: "24/7",
            languages: ["English", "Mandarin", "Japanese", "Korean"],
            specialized_support: {
                name: "Tourist Welcome Desk",
                locations: ["Sydney International Airport T1", "Sydney Central Store"],
                hours: "7AM-7PM Daily"
            }
        }
    },
    // Add similar structures for optus and vodafone
};

// MVNO Provider Template
const mvnoProviders = {
    boost: {
        name: "Boost Mobile",
        type: "mvno",
        network: "telstra", // Parent network
        target_audience: ["Youth", "Tourists", "Value Seekers"],

        // Network Details
        coverage: {
            network_access: "Full Telstra 4G Network",
            speed_caps: "No speed restrictions",
            excluded_features: ["5G Access", "Telstra Air WiFi"],
            roaming_options: {
                international: true,
                supported_countries: 15,
                pricing: "From $5/day in selected countries"
            }
        },

        // Store & Support Locations
        store_locations: {
            cbd: [
                {
                    name: "Boost Mobile Kiosk - World Square",
                    address: "644 George St, Sydney NSW 2000",
                    hours: "Mon-Sat: 9:00-18:00, Sun: 10:00-17:00",
                    phone: "02 9XXX XXXX",
                    nearest_transport: "Town Hall Station (4 min walk)"
                },
                {
                    name: "Boost Mobile - Broadway Shopping Centre",
                    address: "Level 2, 1 Bay Street, Broadway NSW 2007",
                    hours: "Mon-Wed,Fri-Sat: 10:00-18:00, Thu: 10:00-21:00, Sun: 11:00-17:00",
                    phone: "02 9XXX XXXX",
                    nearest_transport: "Central Station (10 min walk)"
                }
            ],
            eastern: [
                {
                    name: "Boost Mobile - Bondi Junction",
                    address: "Westfield Bondi Junction, 500 Oxford St",
                    hours: "Mon-Sat: 9:30-18:00, Sun: 10:00-17:00",
                    phone: "02 9XXX XXXX",
                    nearest_transport: "Bondi Junction Station (direct access)"
                }
            ]
        },

        // Tourist-Specific Features
        tourist_features: {
            sim_activation: {
                process: "Instant activation in store or online",
                requirements: ["Passport", "Local address (hotel acceptable)"],
                activation_time: "Within 2 hours"
            },
            support_languages: ["English"],
            tourist_specific_plans: true
        },

        // Current Plans (as of December 2024)
        plans: {
            prepaid: [
                {
                    name: "Tourist SIM",
                    duration: "28 days",
                    data: "40GB",
                    price: 30,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 20 countries",
                        "Data Banking up to 400GB"
                    ],
                    tourist_bonus: "Extra 15GB first recharge"
                },
                {
                    name: "Premium Tourist SIM",
                    duration: "28 days",
                    data: "100GB",
                    price: 50,
                    features: [
                        "Unlimited Calls/SMS in Australia",
                        "International calls to 35 countries",
                        "Data Banking up to 400GB"
                    ],
                    tourist_bonus: "Extra 25GB first recharge"
                }
            ]
        },

        // Special Partnerships & Deals
        partnerships: [
            {
                partner: "7-Eleven",
                benefit: "Recharge available at all stores",
                locations: "400+ locations in Sydney"
            },
            {
                partner: "Sydney Airport",
                benefit: "SIM pickup at arrival hall",
                details: "Pre-order available online"
            }
        ],

        // Known Issues & Solutions
        known_issues: {
            coverage_gaps: [
                {
                    location: "Train tunnels between stations",
                    impact: "No service in tunnels",
                    solution: "Service resumes at stations"
                }
            ],
            peak_congestion: [
                {
                    location: "Major events (NYE at Circular Quay)",
                    impact: "Slower data speeds",
                    solution: "Premium data plans get priority"
                }
            ]
        }
    }
    // Additional MVNOs to be added...
};

console.log('Telco data loaded:', { networkProviders, mvnoProviders });


// Recommendation Engine for Telco Data
function getDetailedRecommendations(userProfile) {
    console.log('Getting recommendations for profile:', userProfile);

    const recommendations = [];
    let allProviders = [];

    // Combine major providers and MVNOs
    for (const [key, provider] of Object.entries(networkProviders)) {
        console.log(`Processing major provider: ${key}`);
        allProviders.push({ ...provider, score: 0 });
    }
    for (const [key, provider] of Object.entries(mvnoProviders)) {
        console.log(`Processing MVNO: ${key}`);
        allProviders.push({ ...provider, score: 0 });
    }

    // Score each provider
    allProviders = allProviders.map(provider => {
        console.log(`Scoring provider: ${provider.name}`);
        let score = 0;
        const scoringResults = [];

        // Score calculations (existing code)
        const planScore = scorePlanMatch(provider, userProfile);
        score += planScore.score;
        scoringResults.push(planScore);

        const durationBudgetScore = scoreDurationAndBudget(provider, userProfile);
        score += durationBudgetScore.score;
        scoringResults.push(durationBudgetScore);

        const coverageScore = scoreCoverageQuality(provider, userProfile);
        score += coverageScore.score;
        scoringResults.push(coverageScore);

        const featureScore = scoreUserTypeAndFeatures(provider, userProfile);
        score += featureScore.score;
        scoringResults.push(featureScore);

        const supportScore = scoreLocalSupport(provider, userProfile);
        score += supportScore.score;
        scoringResults.push(supportScore);

        const matchedPlan = findBestMatchingPlan(provider, userProfile);
        console.log(`${provider.name} matched plan:`, matchedPlan);

        return {
            ...provider,
            score,
            scoringResults,
            matchExplanation: generateMatchExplanation(scoringResults),
            matchedPlan
        };
    });

    // Filter providers and return recommendations
    const filteredProviders = allProviders.filter(provider => {
        const hasSuitablePlan = provider.matchedPlan !== null;
        const meetsDataNeeds = checkDataRequirements(provider, userProfile.dataNeeds);

        console.log(`Provider ${provider.name}:`, {
            hasSuitablePlan,
            meetsDataNeeds,
            score: provider.score
        });

        return hasSuitablePlan && meetsDataNeeds;
    });

    recommendations.push(...filteredProviders
        .sort((a, b) => b.score - a.score)
        .slice(0, 3));

    console.log('Final recommendations:', recommendations);
    return recommendations;
}

// Enhanced Scoring Functions
function scorePlanMatch(provider, userProfile) {
    let score = 0;
    const reasons = [];

    // Check plan type match
    if (checkPlanTypeMatch(provider, userProfile.planType)) {
        score += 10;
        reasons.push(`Offers ${userProfile.planType} plans as preferred`);
    }

    // Check data allowance match
    const dataMatch = checkDataRequirements(provider, userProfile.dataNeeds);
    if (dataMatch) {
        score += 15;
        reasons.push(`Data allowance matches your ${userProfile.dataNeeds} usage needs`);
    }

    return {
        category: "Plan & Data Match",
        score: Math.min(score, 25),
        reasons
    };
}

function scoreDurationAndBudget(provider, userProfile) {
    let score = 0;
    const reasons = [];

    // Duration match
    const durationMatch = matchesDuration(provider, userProfile.stayDuration);
    if (durationMatch) {
        score += 15;
        reasons.push(`Plan duration suitable for ${userProfile.stayDuration} stay`);
    }

    // Budget match
    const budgetMatch = checkBudgetMatch(provider, userProfile.budget);
    if (budgetMatch) {
        score += 10;
        reasons.push(`Within your budget of $${userProfile.budget}`);
    }

    return {
        category: "Duration & Budget",
        score: Math.min(score, 25),
        reasons
    };
}

function scoreUserTypeAndFeatures(provider, userProfile) {
    let score = 0;
    const reasons = [];

    // User type specific features
    switch (userProfile.userType) {
        case 'tourist':
            if (provider.tourist_features?.tourist_specific_plans) {
                score += 10;
                reasons.push("Specific tourist plans available");
            }
            break;
        case 'student':
            if (provider.partnerships?.some(p => p.benefit.includes('student'))) {
                score += 10;
                reasons.push("Student discounts available");
            }
            break;
        // Add other user types...
    }

    // Requested features
    Object.entries(userProfile.needs).forEach(([feature, needed]) => {
        if (needed && hasFeature(provider, feature)) {
            score += 5;
            reasons.push(`Has requested ${feature} feature`);
        }
    });

    return {
        category: "User Type & Features",
        score: Math.min(score, 20),
        reasons
    };
}

// New Helper Functions
function checkDataRequirements(provider, dataNeeds) {
    const plans = getAllPlans(provider);
    if (!plans || plans.length === 0) return false;

    switch (dataNeeds) {
        case 'light':
            return plans.some(plan => parseInt(plan.data) <= 40);
        case 'medium':
            return plans.some(plan => parseInt(plan.data) > 30 && parseInt(plan.data) <= 100);
        case 'heavy':
            return plans.some(plan => parseInt(plan.data) >= 65);
        case 'unlimited':
            return plans.some(plan =>
                plan.data.toLowerCase().includes('unlimited') ||
                parseInt(plan.data) >= 100
            );
        default:
            return true;
    }
}

function checkPlanTypeMatch(provider, planType) {
    if (planType === 'any') return true;
    return provider.plans?.[planType]?.length > 0;
}

function findBestMatchingPlan(provider, userProfile) {
    // Get all applicable plans based on user's plan type preference
    let plans = [];
    if (userProfile.planType === 'any') {
        if (provider.plans?.prepaid) plans.push(...provider.plans.prepaid);
        if (provider.plans?.postpaid) plans.push(...provider.plans.postpaid);
    } else if (provider.plans?.[userProfile.planType]) {
        plans = provider.plans[userProfile.planType];
    }

    if (!plans || plans.length === 0) return null;

    // Filter plans based on budget (allow 10% over budget for flexibility)
    const maxBudget = parseInt(userProfile.budget) * 1.1;
    plans = plans.filter(plan => plan.price <= maxBudget);

    if (plans.length === 0) return null;

    // Score remaining plans
    return plans.reduce((best, plan) => {
        if (!best) return plan;
        const currentScore = scorePlan(plan, userProfile);
        const bestScore = scorePlan(best, userProfile);
        return currentScore > bestScore ? plan : best;
    }, null);
}


function scorePlan(plan, userProfile) {
    let score = 0;

    // Budget match
    if (plan.price <= parseInt(userProfile.budget)) score += 10;

    // Data match
    const planData = parseInt(plan.data) || 999; // Use 999 for unlimited
    switch (userProfile.dataNeeds) {
        case 'light':
            if (planData <= 30) score += 10;
            break;
        case 'medium':
            if (planData > 30 && planData <= 100) score += 10;
            break;
        case 'heavy':
        case 'unlimited':
            if (planData > 100 || plan.data.toLowerCase().includes('unlimited')) score += 10;
            break;
    }

    return score;
}

function getAllPlans(provider) {
    return [...(provider.plans?.prepaid || []), ...(provider.plans?.postpaid || [])];
}

function checkBudgetMatch(provider, budget) {
    const cheapestPlan = findCheapestSuitablePlan(provider, budget);
    return cheapestPlan && cheapestPlan.price <= parseInt(budget);
}

function hasFeature(provider, feature) {
    switch (feature) {
        case 'coverage':
            return provider.network_technology?.["5G"] !== undefined;
        case 'international':
            return provider.plans?.some(plan =>
                plan.features.some(f => f.toLowerCase().includes('international')));
        case 'entertainment':
            return provider.partnerships?.some(p =>
                p.benefit.toLowerCase().includes('entertainment'));
        case 'speed':
            return provider.network_technology?.["5G"] !== undefined;
        default:
            return false;
    }
}

// Scoring Functions
function scoreUserTypeMatch(provider, userProfile) {
    let score = 0;
    const reasons = [];

    // Tourist-specific scoring
    if (userProfile.userType === 'tourist') {
        // Check for tourist-friendly features
        if (provider.tourist_features?.tourist_specific_plans) {
            score += 10;
            reasons.push("Offers tourist-specific plans");
        }
        if (provider.support?.languages?.length > 1) {
            score += 5;
            reasons.push("Multilingual support available");
        }
        if (provider.store_locations?.[userProfile.location]) {
            score += 5;
            reasons.push("Has stores in your area");
        }
    }

    // Stay duration matching
    if (matchesDuration(provider, userProfile.stayDuration)) {
        score += 5;
        reasons.push("Plan duration matches your stay");
    }

    return {
        category: "User Type Match",
        score: Math.min(score, 20),
        reasons
    };
}

function scoreBudgetMatch(provider, userProfile) {
    let score = 0;
    const reasons = [];
    const budget = parseInt(userProfile.budget);

    // Find cheapest suitable plan
    const cheapestPlan = findCheapestSuitablePlan(provider, userProfile);

    if (cheapestPlan) {
        if (cheapestPlan.price <= budget) {
            // Score based on how well it matches budget
            const budgetEfficiency = (budget - cheapestPlan.price) / budget;
            score = 20 - (budgetEfficiency * 10); // Better score for closer budget match
            reasons.push(`Plan price $${cheapestPlan.price} matches your budget of $${budget}`);
        } else {
            score = Math.max(0, 10 - ((cheapestPlan.price - budget) / 10));
            reasons.push("Plan slightly above your budget but offers good value");
        }
    }

    return {
        category: "Budget Match",
        score: Math.min(score, 20),
        reasons
    };
}

function scoreCoverageQuality(provider, userProfile) {
    let score = 0;
    const reasons = [];

    const coverage = provider.coverage?.sydney?.[userProfile.location];
    if (coverage) {
        switch (coverage.general?.toLowerCase()) {
            case 'excellent':
                score = 20;
                reasons.push("Excellent coverage in your area");
                break;
            case 'very good':
                score = 15;
                reasons.push("Very good coverage in your area");
                break;
            case 'good':
                score = 10;
                reasons.push("Good coverage in your area");
                break;
            default:
                score = 5;
                reasons.push("Basic coverage available");
        }

        // Bonus for complementary WiFi
        if (provider.complementary_wifi?.locations?.[userProfile.location]) {
            score = Math.min(score + 5, 20);
            reasons.push("Free WiFi hotspots in your area");
        }
    }

    return {
        category: "Coverage Quality",
        score,
        reasons
    };
}

function scoreFeatureMatch(provider, userProfile) {
    let score = 0;
    const reasons = [];

    // Check each requested feature
    if (userProfile.needs.coverage && provider.network_technology?.["5G"]) {
        score += 5;
        reasons.push("Premium coverage with 5G access");
    }

    if (userProfile.needs.international && hasInternationalFeatures(provider)) {
        score += 5;
        reasons.push("International calling/roaming available");
    }

    if (userProfile.needs.entertainment && hasEntertainmentPackages(provider)) {
        score += 5;
        reasons.push("Includes entertainment packages");
    }

    if (userProfile.needs.speed && provider.network_technology?.["5G"]) {
        score += 5;
        reasons.push("High-speed 5G network access");
    }

    return {
        category: "Feature Match",
        score: Math.min(score, 20),
        reasons
    };
}

function scoreLocalSupport(provider, userProfile) {
    let score = 0;
    const reasons = [];

    // Physical presence
    if (provider.store_locations?.[userProfile.location]?.length > 0) {
        score += 10;
        reasons.push("Local stores available");
    }

    // Support hours and languages
    if (provider.support?.tourist_support_hours === "24/7") {
        score += 5;
        reasons.push("24/7 support available");
    }

    // Special partnerships or deals
    if (provider.partnerships?.length > 0) {
        score += 5;
        reasons.push("Special deals and partnerships");
    }

    return {
        category: "Local Support",
        score: Math.min(score, 20),
        reasons
    };
}

// Helper Functions
function matchesDuration(provider, stayDuration) {
    const plans = provider.plans?.prepaid || provider.plans?.postpaid;
    if (!plans) return false;

    switch (stayDuration) {
        case 'short':
            return plans.some(plan => plan.duration <= 30);
        case 'medium':
            return plans.some(plan => plan.duration <= 90);
        case 'long':
            return plans.some(plan => !plan.duration || plan.duration > 90);
        default:
            return true;
    }
}

function findCheapestSuitablePlan(provider, userProfile) {
    const plans = provider.plans?.[userProfile.planType] ||
        provider.plans?.prepaid ||
        provider.plans?.postpaid;

    if (!plans) return null;

    return plans.reduce((cheapest, plan) => {
        if (!cheapest || plan.price < cheapest.price) {
            return plan;
        }
        return cheapest;
    }, null);
}

function generateMatchExplanation(scoringResults) {
    return scoringResults.map(result => ({
        category: result.category,
        score: result.score,
        reasons: result.reasons
    }));
}

// Feature Check Helpers
function hasInternationalFeatures(provider) {
    return provider.plans?.prepaid?.some(plan =>
        plan.features.some(f => f.toLowerCase().includes('international'))) ||
        provider.plans?.postpaid?.some(plan =>
            plan.features.some(f => f.toLowerCase().includes('international')));
}

function hasEntertainmentPackages(provider) {
    return provider.partnerships?.some(p =>
        p.benefit.toLowerCase().includes('streaming') ||
        p.benefit.toLowerCase().includes('entertainment'));
}

// Add these functions to your telco-data.js file, before the exports

function findCheapestPlan(provider) {
    // Get all plans from both prepaid and postpaid
    const allPlans = [];

    // Add prepaid plans if they exist
    if (provider.plans && provider.plans.prepaid) {
        allPlans.push(...provider.plans.prepaid);
    }

    // Add postpaid plans if they exist
    if (provider.plans && provider.plans.postpaid) {
        allPlans.push(...provider.plans.postpaid);
    }

    // If no plans found, return null
    if (allPlans.length === 0) {
        return null;
    }

    // Find the cheapest plan
    return allPlans.reduce((cheapest, plan) => {
        if (!cheapest || plan.price < cheapest.price) {
            return plan;
        }
        return cheapest;
    }, null);
}

function findBestMatchingPlan(provider, userProfile) {
    // Get all applicable plans based on user's plan type preference
    let plans = [];
    if (userProfile.planType === 'any') {
        if (provider.plans?.prepaid) plans.push(...provider.plans.prepaid);
        if (provider.plans?.postpaid) plans.push(...provider.plans.postpaid);
    } else if (provider.plans?.[userProfile.planType]) {
        plans = provider.plans[userProfile.planType];
    }

    if (plans.length === 0) return null;

    return plans.reduce((best, plan) => {
        if (!best) return plan;

        const currentScore = scorePlan(plan, userProfile);
        const bestScore = scorePlan(best, userProfile);

        return currentScore > bestScore ? plan : best;
    }, null);
}

function scorePlan(plan, userProfile) {
    let score = 0;

    // Budget match
    if (plan.price <= parseInt(userProfile.budget)) {
        score += 10;
        // Better score for plans closer to budget
        const budgetEfficiency = (parseInt(userProfile.budget) - plan.price) / parseInt(userProfile.budget);
        score += (1 - budgetEfficiency) * 5; // More points for plans closer to max budget
    }

    // Data match
    const planData = parseInt(plan.data) || 0;
    switch (userProfile.dataNeeds) {
        case 'light':
            if (planData <= 30) score += 10;
            break;
        case 'medium':
            if (planData > 30 && planData <= 100) score += 10;
            break;
        case 'heavy':
            if (planData > 100) score += 10;
            break;
        case 'unlimited':
            if (plan.data.toLowerCase().includes('unlimited')) score += 10;
            break;
    }

    // Duration match
    const planDuration = plan.duration || 30; // Assume monthly if not specified
    switch (userProfile.stayDuration) {
        case 'short':
            if (planDuration <= 30) score += 5;
            break;
        case 'medium':
            if (planDuration >= 28 && planDuration <= 90) score += 5;
            break;
        case 'long':
        case 'permanent':
            if (planDuration >= 28) score += 5;
            break;
    }

    return score;
}

// Make sure to add these to your window exports at the bottom of telco-data.js
window.findBestMatchingPlan = findBestMatchingPlan;
window.scorePlan = scorePlan;



// Export everything
window.networkProviders = networkProviders;
window.mvnoProviders = mvnoProviders;
window.getDetailedRecommendations = getDetailedRecommendations;
window.getAllPlans = getAllPlans;
window.findCheapestPlan = findCheapestPlan;
window.hasFeature = hasFeature;