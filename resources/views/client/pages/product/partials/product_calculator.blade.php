@php
    // Extract numeric values from custom attributes
    $hashrate = $product->getCustomAttribute('hashrate', '100');
    $power = $product->getCustomAttribute('power', '3000');

    // Extract numeric value from hashrate string (e.g., "293Th/s" -> 293)
    preg_match('/[\d.]+/', $hashrate, $hashrateMatches);
    $hashrateValue = $hashrateMatches[0] ?? 100;

    // Extract numeric value from power string (e.g., "5567W" -> 5567)
    preg_match('/[\d.]+/', $power, $powerMatches);
    $powerValue = $powerMatches[0] ?? 3000;

    // Get price
    $primaryVariant = $product->variants->first();
    $price = $primaryVariant?->prices->first()?->price ?? 500000;
    $priceInDollars = $price / 100;
@endphp

<div class="container mb-5">
    <style>
        .calculator-container {
            border-radius: 10px;
            width: 100%;
            position: relative;
            padding: 20px 0;
        }

        .calculator-container .calculator-title {
            font-size: 18px;
            font-weight: 700;
            color: #242424;
            margin-bottom: 15px;
        }

        .calculator-container .calculator-title .product-name {
            color: #25C385;
        }

        .calculator-container .calculator-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .calculator-container .calculator_left {
            flex: 1;
            min-width: 300px;
        }

        .calculator-container .calculator_right {
            width: 300px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 18px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .calculator-container .input-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 12px;
        }

        .calculator-container .input-group {
            display: flex;
            flex-direction: column;
        }

        .calculator-container .input-group label {
            font-size: 11px;
            color: #242424;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .calculator-container .input-group input,
        .calculator-container .input-group select {
            padding: 6px 10px;
            width: 100%;
            height: 34px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            background-color: #f8f9fa;
            font-size: 12px;
            transition: all 0.3s ease;
            color: #242424;
        }

        .calculator-container .input-group input:focus,
        .calculator-container .input-group select:focus {
            outline: none;
            border-color: #25C385;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(37, 195, 133, 0.1);
        }

        .calculator-container .input-group input::placeholder {
            color: #adb5bd;
        }

        .calculator-container #calculateButton {
            padding: 10px 20px;
            background: linear-gradient(135deg, #25C385 0%, #1ea86e 100%);
            color: white;
            font-size: 13px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .calculator-container #calculateButton:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(37, 195, 133, 0.4);
        }

        .calculator-container #calculateButton:active {
            transform: translateY(0);
        }

        .calculator-container .calc-note {
            margin-top: 8px;
            color: #6c757d;
            font-size: 10px;
            line-height: 1.4;
        }

        .calculator-container .calculator_right h3 {
            font-size: 16px;
            font-weight: 700;
            color: #242424;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid #25C385;
        }

        .calculator-container .result-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .calculator-container .result-item:last-child {
            border-bottom: none;
        }

        .calculator-container .result-item .label {
            font-size: 12px;
            font-weight: 600;
            color: #495057;
        }

        .calculator-container .result-item .value {
            font-size: 13px;
            font-weight: 700;
            color: #242424;
        }

        .calculator-container .result-item .value.profit-positive {
            color: #25C385;
        }

        .calculator-container .result-item .value.profit-negative {
            color: #dc3545;
        }

        .calculator-container .result-detail {
            background: #fff;
            border-radius: 6px;
            padding: 10px;
            margin-top: 10px;
        }

        .calculator-container .result-detail-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .calculator-container .result-detail-row:last-child {
            margin-bottom: 0;
        }

        .calculator-container .coin-select-wrapper {
            position: relative;
        }

        .calculator-container .coin-select-wrapper select {
            appearance: none;
            padding-right: 40px;
        }

        .calculator-container .coin-select-wrapper::after {
            content: '';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid #6c757d;
            pointer-events: none;
        }

        @media (max-width: 992px) {
            .calculator-container .calculator_right {
                width: 100%;
            }

            .calculator-container .input-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 8px;
            }

            .calculator-container .input-group label {
                font-size: 10px;
            }

            .calculator-container .input-group input,
            .calculator-container .input-group select {
                padding: 5px 8px;
                height: 32px;
                font-size: 11px;
            }
        }

        @media (max-width: 768px) {
            .calculator-container .input-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
        }

        @media (max-width: 576px) {
            .calculator-container .input-grid {
                grid-template-columns: 1fr;
            }

            .calculator-container #calculateButton {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="calculator-container">
        <h2 class="calculator-title">
            <span class="product-name">{{ $product->name }}</span> Profitability Calculator
        </h2>

        <div class="calculator-wrapper">
            <div class="calculator_left">
                <div class="input-grid">
                    <div class="input-group">
                        <label for="coinSelect">Coin</label>
                        <div class="coin-select-wrapper">
                            <select id="coinSelect" class="form-select">
                                @if($product->minableCoins && $product->minableCoins->count() > 0)
                                    @foreach($product->minableCoins as $coin)
                                        <option value="{{ $coin->symbol }}"
                                                data-difficulty="{{ $coin->difficulty ?? '1e20' }}"
                                                data-block-time="{{ $coin->block_time ?? 600 }}"
                                                data-block-reward="{{ $coin->block_reward ?? 1 }}"
                                                data-price="{{ $coin->default_price ?? 1 }}"
                                                data-algorithm="{{ $coin->algorithm }}">
                                            {{ $coin->symbol }} ({{ $coin->name }})
                                        </option>
                                    @endforeach
                                @else
                                    {{-- Fallback to default coins if no minable coins specified --}}
                                    <option value="BTC" data-difficulty="1.0590823241980708e+21" data-block-time="600" data-block-reward="3.125" data-price="100000" data-algorithm="SHA-256">BTC (Bitcoin)</option>
                                    <option value="LTC" data-difficulty="3.5e+7" data-block-time="150" data-block-reward="6.25" data-price="100" data-algorithm="Scrypt">LTC (Litecoin)</option>
                                    <option value="DOGE" data-difficulty="2.5e+7" data-block-time="60" data-block-reward="10000" data-price="0.15" data-algorithm="Scrypt">DOGE (Dogecoin)</option>
                                    <option value="KAS" data-difficulty="1.5e+15" data-block-time="1" data-block-reward="50" data-price="0.15" data-algorithm="kHeavyHash">KAS (Kaspa)</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="coinPrice">Coin Price (USD)</label>
                        <input type="number" id="coinPrice" placeholder="Enter coin price" step="0.01" value="100000">
                    </div>

                    <div class="input-group">
                        <label for="electricityCost">Electricity Cost ($/kWh)</label>
                        <input type="number" id="electricityCost" placeholder="Enter electricity cost" step="0.01" value="0.05">
                    </div>

                    <div class="input-group">
                        <label for="poolFee">Pool Fee (%)</label>
                        <input type="number" id="poolFee" placeholder="Enter pool fee" step="0.1" value="1">
                    </div>
                </div>

                <div class="input-grid">
                    <div class="input-group">
                        <label for="hashrate">Hashrate (<span id="hashrateUnit">TH/s</span>)</label>
                        <input type="number" id="hashrate" placeholder="Enter hashrate" step="0.1" value="{{ $hashrateValue }}">
                    </div>

                    <div class="input-group">
                        <label for="power">Power Consumption (W)</label>
                        <input type="number" id="power" placeholder="Enter power" step="1" value="{{ $powerValue }}">
                    </div>

                    <div class="input-group">
                        <label for="minerPrice">Miner Price (USD)</label>
                        <input type="number" id="minerPrice" placeholder="Enter miner price" step="1" value="{{ $priceInDollars }}">
                    </div>

                    <div class="input-group">
                        <label for="minerCount">Number of Miners</label>
                        <input type="number" id="minerCount" placeholder="Enter quantity" min="1" value="1">
                    </div>
                </div>

                <button id="calculateButton">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="4" y="2" width="16" height="20" rx="2"/>
                        <line x1="8" y1="6" x2="16" y2="6"/>
                        <line x1="8" y1="10" x2="16" y2="10"/>
                        <line x1="8" y1="14" x2="12" y2="14"/>
                        <line x1="8" y1="18" x2="12" y2="18"/>
                    </svg>
                    Calculate Profitability
                </button>

                <p class="calc-note">
                    * Results are estimates based on current network difficulty and may vary.
                    Actual results depend on network conditions, pool luck, and other factors.
                </p>
            </div>

            <div class="calculator_right">
                <h3>Calculation Results</h3>

                <div class="result-item">
                    <span class="label">Daily Profit</span>
                    <span class="value profit-positive" id="dailyProfit">$0.00</span>
                </div>

                <div class="result-item">
                    <span class="label">Daily Reward (<span id="rewardCoin">BTC</span>)</span>
                    <span class="value" id="dailyReward">0.00000000</span>
                </div>

                <div class="result-item">
                    <span class="label">Monthly Profit</span>
                    <span class="value profit-positive" id="monthlyProfit">$0.00</span>
                </div>

                <div class="result-item">
                    <span class="label">Payback Period</span>
                    <span class="value" id="paybackPeriod">-- months</span>
                </div>

                <div class="result-detail">
                    <div class="result-detail-row">
                        <span>Daily Revenue</span>
                        <span id="dailyRevenue">$0.00</span>
                    </div>
                    <div class="result-detail-row">
                        <span>Daily Electricity Cost</span>
                        <span id="dailyElectricity">$0.00</span>
                    </div>
                    <div class="result-detail-row">
                        <span>Daily Pool Fee</span>
                        <span id="dailyPoolFee">$0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const coinSelect = document.getElementById('coinSelect');
            const coinPrice = document.getElementById('coinPrice');
            const electricityCost = document.getElementById('electricityCost');
            const poolFee = document.getElementById('poolFee');
            const hashrate = document.getElementById('hashrate');
            const power = document.getElementById('power');
            const minerPrice = document.getElementById('minerPrice');
            const minerCount = document.getElementById('minerCount');
            const calculateBtn = document.getElementById('calculateButton');
            const hashrateUnit = document.getElementById('hashrateUnit');
            const rewardCoin = document.getElementById('rewardCoin');

            // Build coin configurations dynamically from select options
            const coinConfigs = {};
            Array.from(coinSelect.options).forEach(option => {
                const algorithm = option.dataset.algorithm || '';
                let unit = 'TH/s';
                let multiplier = 1e12;

                // Determine unit based on algorithm
                if (algorithm.toLowerCase().includes('scrypt') || algorithm.toLowerCase().includes('ethash')) {
                    unit = 'MH/s';
                    multiplier = 1e6;
                } else if (algorithm.toLowerCase().includes('sha')) {
                    unit = 'TH/s';
                    multiplier = 1e12;
                } else if (algorithm.toLowerCase().includes('kheavyhash') || algorithm.toLowerCase().includes('blake')) {
                    unit = 'TH/s';
                    multiplier = 1e12;
                } else {
                    unit = 'GH/s';
                    multiplier = 1e9;
                }

                coinConfigs[option.value] = {
                    unit: unit,
                    multiplier: multiplier,
                    defaultPrice: parseFloat(option.dataset.price) || 1
                };
            });

            // Update unit when coin changes
            coinSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const coin = this.value;
                const config = coinConfigs[coin];
                hashrateUnit.textContent = config.unit;
                rewardCoin.textContent = coin;
                coinPrice.value = config.defaultPrice;
            });

            // Set initial values
            const initialCoin = coinSelect.value;
            const initialConfig = coinConfigs[initialCoin];
            if (initialConfig) {
                hashrateUnit.textContent = initialConfig.unit;
                rewardCoin.textContent = initialCoin;
                coinPrice.value = initialConfig.defaultPrice;
            }

            // Calculate mining profitability
            function calculateProfitability() {
                const selectedOption = coinSelect.options[coinSelect.selectedIndex];
                const coin = coinSelect.value;
                const config = coinConfigs[coin];

                // Get values from inputs
                const price = parseFloat(coinPrice.value) || 0;
                const elecCost = parseFloat(electricityCost.value) || 0;
                const poolFeePercent = parseFloat(poolFee.value) || 0;
                const hashrateValue = parseFloat(hashrate.value) || 0;
                const powerValue = parseFloat(power.value) || 0;
                const minerPriceValue = parseFloat(minerPrice.value) || 0;
                const count = parseInt(minerCount.value) || 1;

                // Get coin parameters
                const difficulty = parseFloat(selectedOption.dataset.difficulty);
                const blockTime = parseFloat(selectedOption.dataset.blockTime);
                const blockReward = parseFloat(selectedOption.dataset.blockReward);

                // Calculate hashrate in H/s
                const totalHashrate = hashrateValue * config.multiplier * count;

                // Calculate daily coins mined
                // Formula: (hashrate * blockReward * 86400) / (difficulty * 2^32)
                const dailyCoins = (totalHashrate * blockReward * 86400) / (difficulty * Math.pow(2, 32));

                // Calculate revenues and costs
                const dailyRevenueValue = dailyCoins * price;
                const dailyPoolFeeValue = dailyRevenueValue * (poolFeePercent / 100);
                const dailyElectricityCost = (powerValue * count * 24 / 1000) * elecCost;
                const dailyProfitValue = dailyRevenueValue - dailyPoolFeeValue - dailyElectricityCost;
                const monthlyProfitValue = dailyProfitValue * 30;

                // Calculate payback period
                const totalInvestment = minerPriceValue * count;
                let paybackMonths = 0;
                if (dailyProfitValue > 0) {
                    paybackMonths = totalInvestment / (dailyProfitValue * 30);
                }

                // Update UI
                const dailyProfitEl = document.getElementById('dailyProfit');
                dailyProfitEl.textContent = formatCurrency(dailyProfitValue);
                dailyProfitEl.className = 'value ' + (dailyProfitValue >= 0 ? 'profit-positive' : 'profit-negative');

                document.getElementById('dailyReward').textContent = formatCrypto(dailyCoins);

                const monthlyProfitEl = document.getElementById('monthlyProfit');
                monthlyProfitEl.textContent = formatCurrency(monthlyProfitValue);
                monthlyProfitEl.className = 'value ' + (monthlyProfitValue >= 0 ? 'profit-positive' : 'profit-negative');

                if (dailyProfitValue > 0) {
                    document.getElementById('paybackPeriod').textContent = paybackMonths.toFixed(1) + ' months';
                } else {
                    document.getElementById('paybackPeriod').textContent = 'Never (negative profit)';
                }

                document.getElementById('dailyRevenue').textContent = formatCurrency(dailyRevenueValue);
                document.getElementById('dailyElectricity').textContent = '-' + formatCurrency(dailyElectricityCost);
                document.getElementById('dailyPoolFee').textContent = '-' + formatCurrency(dailyPoolFeeValue);
            }

            function formatCurrency(value) {
                const sign = value < 0 ? '-' : '';
                return sign + '$' + Math.abs(value).toFixed(2);
            }

            function formatCrypto(value) {
                if (value < 0.00001) {
                    return value.toExponential(4);
                }
                return value.toFixed(8);
            }

            // Calculate on button click
            calculateBtn.addEventListener('click', calculateProfitability);

            // Auto-calculate on input change (with debounce)
            let debounceTimer;
            const inputs = [coinPrice, electricityCost, poolFee, hashrate, power, minerPrice, minerCount];
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(calculateProfitability, 300);
                });
            });

            coinSelect.addEventListener('change', function() {
                setTimeout(calculateProfitability, 100);
            });

            // Initial calculation
            calculateProfitability();
        });
    </script>
</div>
