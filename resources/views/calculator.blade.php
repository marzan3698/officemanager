@extends('layouts.app')

@section('content')
<div class="header mb-4">
    <div class="d-flex justify-between align-center">
        <h1>ক্যালকুলেটর ও হিসাব</h1>
    </div>
</div>

<div class="content">
    <div class="d-flex mb-4" style="gap: 8px;">
        <button class="btn btn-primary tab-btn" id="btn-basic" onclick="showTab('basic')" style="flex: 1; padding: 10px; font-size: 14px;">বেসিক</button>
        <button class="btn tab-btn" id="btn-binary" onclick="showTab('binary')" style="flex: 1; padding: 10px; font-size: 14px; background: #E5E7EB; color: #374151;">বাইনারি</button>
        <button class="btn tab-btn" id="btn-gen" onclick="showTab('gen')" style="flex: 1; padding: 10px; font-size: 14px; background: #E5E7EB; color: #374151;">জেনারেশন</button>
    </div>

    <!-- ==================== BASIC CALCULATOR ==================== -->
    <div id="tab-basic">
        <x-card>
            <div style="background: #111827; color: white; padding: 20px; border-radius: 12px; text-align: right; font-size: 28px; margin-bottom: 16px; min-height: 70px; word-break: break-all; font-weight: 600;" id="calc-display">0</div>
            
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                <button class="calc-btn op" onclick="clearCalc()">C</button>
                <button class="calc-btn op" onclick="appendCalc('(')">(</button>
                <button class="calc-btn op" onclick="appendCalc(')')">)</button>
                <button class="calc-btn op" onclick="appendCalc('/')">÷</button>
                
                <button class="calc-btn" onclick="appendCalc('7')">7</button>
                <button class="calc-btn" onclick="appendCalc('8')">8</button>
                <button class="calc-btn" onclick="appendCalc('9')">9</button>
                <button class="calc-btn op" onclick="appendCalc('*')">×</button>
                
                <button class="calc-btn" onclick="appendCalc('4')">4</button>
                <button class="calc-btn" onclick="appendCalc('5')">5</button>
                <button class="calc-btn" onclick="appendCalc('6')">6</button>
                <button class="calc-btn op" onclick="appendCalc('-')">-</button>
                
                <button class="calc-btn" onclick="appendCalc('1')">1</button>
                <button class="calc-btn" onclick="appendCalc('2')">2</button>
                <button class="calc-btn" onclick="appendCalc('3')">3</button>
                <button class="calc-btn op" onclick="appendCalc('+')">+</button>
                
                <button class="calc-btn" onclick="appendCalc('0')">0</button>
                <button class="calc-btn" onclick="appendCalc('.')">.</button>
                <button class="calc-btn" onclick="backspaceCalc()">⌫</button>
                <button class="calc-btn" style="background: var(--primary); color: white;" onclick="calculate()">=</button>
            </div>
        </x-card>
    </div>

    <!-- ==================== BINARY CALCULATOR ==================== -->
    <div id="tab-binary" style="display: none;">
        <x-card class="mb-4">
            <h3 class="mb-4" style="font-size: 16px;">🔀 বাইনারি ম্যাচিং ইনকাম হিসাব</h3>
            
            <label style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">লেফট সাইড সেলস / পয়েন্ট (BV)</label>
            <input type="number" id="bin-left" value="" placeholder="উদাহরণ: 5000">
            
            <label style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">রাইট সাইড সেলস / পয়েন্ট (BV)</label>
            <input type="number" id="bin-right" value="" placeholder="উদাহরণ: 3000">
            
            <label style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">ম্যাচিং বোনাস পারসেন্টেজ (%)</label>
            <input type="number" id="bin-percent" value="10" placeholder="উদাহরণ: 10">
            
            <label style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">ডেইলি ক্যাপিং লিমিট (৳) — ঐচ্ছিক</label>
            <input type="number" id="bin-cap" value="" placeholder="উদাহরণ: 5000 (খালি রাখলে ক্যাপিং নেই)">
            
            <div class="d-flex" style="gap: 8px;">
                <button class="btn btn-primary" style="flex: 3;" onclick="calcBinary()">হিসাব করুন</button>
                <button class="btn" style="flex: 1; background: #FDE8E8; color: var(--danger);" onclick="resetBinary()">রিসেট</button>
            </div>
        </x-card>
        
        <div id="bin-result" style="display:none;">
            <x-card style="border-left: 4px solid var(--success);">
                <div class="section-title" style="font-size: 14px; margin-bottom: 8px;">📊 বাইনারি ম্যাচিং রেজাল্ট</div>
                <div id="bin-result-content"></div>
            </x-card>
        </div>
    </div>

    <!-- ==================== GENERATION CALCULATOR ==================== -->
    <div id="tab-gen" style="display: none;">
        <x-card class="mb-4">
            <h3 class="mb-4" style="font-size: 16px;">🌳 জেনারেশন ইনকাম হিসাব</h3>
            
            <div id="gen-levels-container">
                <div class="gen-level-row mb-2" data-level="1">
                    <label style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">১ম জেনারেশন — সেলস/পয়েন্ট ও বোনাস %</label>
                    <div class="d-flex" style="gap: 8px;">
                        <input type="number" class="gen-sales" placeholder="সেলস (BV)" style="flex: 2;">
                        <input type="number" class="gen-percent" value="5" placeholder="%" style="flex: 1;">
                    </div>
                </div>
                <div class="gen-level-row mb-2" data-level="2">
                    <label style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">২য় জেনারেশন — সেলস/পয়েন্ট ও বোনাস %</label>
                    <div class="d-flex" style="gap: 8px;">
                        <input type="number" class="gen-sales" placeholder="সেলস (BV)" style="flex: 2;">
                        <input type="number" class="gen-percent" value="3" placeholder="%" style="flex: 1;">
                    </div>
                </div>
                <div class="gen-level-row mb-2" data-level="3">
                    <label style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">৩য় জেনারেশন — সেলস/পয়েন্ট ও বোনাস %</label>
                    <div class="d-flex" style="gap: 8px;">
                        <input type="number" class="gen-sales" placeholder="সেলস (BV)" style="flex: 2;">
                        <input type="number" class="gen-percent" value="2" placeholder="%" style="flex: 1;">
                    </div>
                </div>
            </div>
            
            <button class="btn mb-2" style="background: #E5E7EB; color: var(--primary); padding: 8px; font-size: 13px;" onclick="addGenLevel()">+ আরেকটি জেনারেশন যুক্ত করুন</button>
            
            <div class="d-flex" style="gap: 8px;">
                <button class="btn btn-primary" style="flex: 3;" onclick="calcGen()">হিসাব করুন</button>
                <button class="btn" style="flex: 1; background: #FDE8E8; color: var(--danger);" onclick="resetGen()">রিসেট</button>
            </div>
        </x-card>
        
        <div id="gen-result" style="display:none;">
            <x-card style="border-left: 4px solid var(--success);">
                <div class="section-title" style="font-size: 14px; margin-bottom: 8px;">📊 জেনারেশন ইনকাম রেজাল্ট</div>
                <div id="gen-result-content"></div>
            </x-card>
        </div>
    </div>

</div>

<style>
    .calc-btn {
        background: #F3F4F6;
        border: none;
        border-radius: 8px;
        padding: 16px 0;
        font-size: 20px;
        font-weight: 600;
        color: var(--text-primary);
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: background 0.15s;
    }
    .calc-btn:active {
        background: #D1D5DB;
    }
    .calc-btn.op {
        background: #E5E7EB;
        color: var(--primary);
    }
    .result-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid var(--border);
        font-size: 14px;
    }
    .result-row:last-child {
        border-bottom: none;
    }
    .result-row .label {
        color: var(--text-secondary);
    }
    .result-row .value {
        font-weight: 600;
        color: var(--text-primary);
    }
    .result-summary {
        margin-top: 12px;
        padding: 12px;
        background: #F0FDF4;
        border-radius: 8px;
        font-size: 13px;
        line-height: 1.8;
        color: #166534;
    }
    .result-total {
        margin-top: 12px;
        padding: 12px;
        background: var(--primary);
        border-radius: 8px;
        text-align: center;
        color: white;
        font-size: 18px;
        font-weight: 700;
    }
    .mt-2 { margin-top: 8px; }
</style>

<script>
    // ==================== TAB SWITCHING ====================
    function showTab(tab) {
        document.getElementById('tab-basic').style.display = 'none';
        document.getElementById('tab-binary').style.display = 'none';
        document.getElementById('tab-gen').style.display = 'none';
        
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('btn-primary');
            b.style.background = '#E5E7EB';
            b.style.color = '#374151';
        });
        
        document.getElementById('tab-' + tab).style.display = 'block';
        let activeBtn = document.getElementById('btn-' + tab);
        activeBtn.classList.add('btn-primary');
        activeBtn.style.background = 'var(--primary)';
        activeBtn.style.color = 'white';
    }

    // ==================== BASIC CALCULATOR ====================
    let calcStr = '';
    const display = document.getElementById('calc-display');
    
    function appendCalc(val) {
        calcStr += val;
        display.innerText = calcStr;
    }
    function clearCalc() {
        calcStr = '';
        display.innerText = '0';
    }
    function backspaceCalc() {
        calcStr = calcStr.slice(0, -1);
        display.innerText = calcStr || '0';
    }
    function calculate() {
        try {
            let result = eval(calcStr);
            calcStr = result.toString();
            display.innerText = Number.isInteger(result) ? result : parseFloat(result.toFixed(4));
        } catch(e) {
            display.innerText = 'Error';
            calcStr = '';
        }
    }

    // ==================== BINARY CALCULATOR ====================
    function calcBinary() {
        let left = parseFloat(document.getElementById('bin-left').value) || 0;
        let right = parseFloat(document.getElementById('bin-right').value) || 0;
        let percent = parseFloat(document.getElementById('bin-percent').value) || 0;
        let cap = parseFloat(document.getElementById('bin-cap').value) || 0;
        
        let weakSide = Math.min(left, right);
        let strongSide = Math.max(left, right);
        let carryForward = strongSide - weakSide;
        let bonus = weakSide * (percent / 100);
        let originalBonus = bonus;
        let capped = false;
        
        if (cap > 0 && bonus > cap) {
            bonus = cap;
            capped = true;
        }
        
        let weakLabel = left <= right ? 'লেফট' : 'রাইট';
        let strongLabel = left > right ? 'লেফট' : 'রাইট';
        
        let html = '';
        html += `<div class="result-row"><span class="label">লেফট সাইড পয়েন্ট</span><span class="value">${left.toLocaleString('bn-BD')} BV</span></div>`;
        html += `<div class="result-row"><span class="label">রাইট সাইড পয়েন্ট</span><span class="value">${right.toLocaleString('bn-BD')} BV</span></div>`;
        html += `<div class="result-row"><span class="label">উইক সাইড (${weakLabel})</span><span class="value">${weakSide.toLocaleString('bn-BD')} BV</span></div>`;
        html += `<div class="result-row"><span class="label">ম্যাচিং পয়েন্ট</span><span class="value">${weakSide.toLocaleString('bn-BD')} BV</span></div>`;
        html += `<div class="result-row"><span class="label">বোনাস রেট</span><span class="value">${percent}%</span></div>`;
        html += `<div class="result-row"><span class="label">ক্যারি ফরওয়ার্ড (${strongLabel})</span><span class="value">${carryForward.toLocaleString('bn-BD')} BV</span></div>`;
        
        if (capped) {
            html += `<div class="result-row"><span class="label" style="color: var(--warning);">ক্যাপিং লিমিট</span><span class="value" style="color: var(--warning);">${cap.toLocaleString('bn-BD')}৳ (আসল: ${originalBonus.toLocaleString('bn-BD')}৳)</span></div>`;
        }
        
        html += `<div class="result-total">বাইনারি বোনাস: ${bonus.toLocaleString('bn-BD')}৳</div>`;
        
        // Bangla Summary
        html += `<div class="result-summary">`;
        html += `📝 <strong>সারসংক্ষেপ:</strong><br>`;
        html += `আপনার লেফট সাইডে ${left.toLocaleString('bn-BD')} BV এবং রাইট সাইডে ${right.toLocaleString('bn-BD')} BV পয়েন্ট আছে। `;
        html += `${weakLabel} সাইড উইক (দুর্বল) সাইড হওয়ায় ম্যাচিং হবে ${weakSide.toLocaleString('bn-BD')} BV এর উপর। `;
        html += `${percent}% হারে আপনার বাইনারি বোনাস হবে <strong>${bonus.toLocaleString('bn-BD')}৳</strong>। `;
        if (carryForward > 0) {
            html += `${strongLabel} সাইডে ${carryForward.toLocaleString('bn-BD')} BV ক্যারি ফরওয়ার্ড (জমা) থাকবে।`;
        }
        if (capped) {
            html += `<br>⚠️ ক্যাপিং লিমিটের কারণে আসল বোনাস ${originalBonus.toLocaleString('bn-BD')}৳ এর পরিবর্তে ${cap.toLocaleString('bn-BD')}৳ পাবেন।`;
        }
        html += `</div>`;
        
        document.getElementById('bin-result-content').innerHTML = html;
        document.getElementById('bin-result').style.display = 'block';
    }
    
    function resetBinary() {
        document.getElementById('bin-left').value = '';
        document.getElementById('bin-right').value = '';
        document.getElementById('bin-percent').value = '10';
        document.getElementById('bin-cap').value = '';
        document.getElementById('bin-result').style.display = 'none';
    }

    // ==================== GENERATION CALCULATOR ====================
    let genLevelCount = 3;
    const bnOrdinals = ['১ম', '২য়', '৩য়', '৪র্থ', '৫ম', '৬ষ্ঠ', '৭ম', '৮ম', '৯ম', '১০ম'];
    
    function addGenLevel() {
        genLevelCount++;
        let label = bnOrdinals[genLevelCount - 1] || genLevelCount + 'তম';
        let html = `
            <div class="gen-level-row mb-2" data-level="${genLevelCount}">
                <label style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">${label} জেনারেশন — সেলস/পয়েন্ট ও বোনাস %</label>
                <div class="d-flex" style="gap: 8px;">
                    <input type="number" class="gen-sales" placeholder="সেলস (BV)" style="flex: 2;">
                    <input type="number" class="gen-percent" value="1" placeholder="%" style="flex: 1;">
                </div>
            </div>`;
        document.getElementById('gen-levels-container').insertAdjacentHTML('beforeend', html);
    }
    
    function calcGen() {
        let rows = document.querySelectorAll('.gen-level-row');
        let totalBonus = 0;
        let details = [];
        
        rows.forEach((row, i) => {
            let sales = parseFloat(row.querySelector('.gen-sales').value) || 0;
            let percent = parseFloat(row.querySelector('.gen-percent').value) || 0;
            let bonus = sales * (percent / 100);
            let label = bnOrdinals[i] || (i + 1) + 'তম';
            details.push({ label, sales, percent, bonus });
            totalBonus += bonus;
        });
        
        let html = '';
        details.forEach(d => {
            if (d.sales > 0) {
                html += `<div class="result-row"><span class="label">${d.label} জেনারেশন (${d.sales.toLocaleString('bn-BD')} × ${d.percent}%)</span><span class="value">${d.bonus.toLocaleString('bn-BD')}৳</span></div>`;
            }
        });
        
        html += `<div class="result-total">মোট জেনারেশন ইনকাম: ${totalBonus.toLocaleString('bn-BD')}৳</div>`;
        
        // Bangla Summary
        html += `<div class="result-summary">`;
        html += `📝 <strong>সারসংক্ষেপ:</strong><br>`;
        let activeLevels = details.filter(d => d.sales > 0);
        if (activeLevels.length > 0) {
            html += `আপনার মোট ${activeLevels.length}টি জেনারেশন থেকে ইনকাম হিসাব করা হয়েছে। `;
            activeLevels.forEach(d => {
                html += `${d.label} জেনারেশনে ${d.sales.toLocaleString('bn-BD')} BV সেলসের উপর ${d.percent}% হারে বোনাস ${d.bonus.toLocaleString('bn-BD')}৳। `;
            });
            html += `<br><strong>সর্বমোট জেনারেশন ইনকাম: ${totalBonus.toLocaleString('bn-BD')}৳</strong>`;
        } else {
            html += `কোনো জেনারেশনে সেলস দেওয়া হয়নি। অনুগ্রহ করে সেলস পয়েন্ট ইনপুট দিন।`;
        }
        html += `</div>`;
        
        document.getElementById('gen-result-content').innerHTML = html;
        document.getElementById('gen-result').style.display = 'block';
    }
    
    function resetGen() {
        // Reset to default 3 levels
        document.getElementById('gen-levels-container').innerHTML = `
            <div class="gen-level-row mb-2" data-level="1">
                <label style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">১ম জেনারেশন — সেলস/পয়েন্ট ও বোনাস %</label>
                <div class="d-flex" style="gap: 8px;">
                    <input type="number" class="gen-sales" placeholder="সেলস (BV)" style="flex: 2;">
                    <input type="number" class="gen-percent" value="5" placeholder="%" style="flex: 1;">
                </div>
            </div>
            <div class="gen-level-row mb-2" data-level="2">
                <label style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">২য় জেনারেশন — সেলস/পয়েন্ট ও বোনাস %</label>
                <div class="d-flex" style="gap: 8px;">
                    <input type="number" class="gen-sales" placeholder="সেলস (BV)" style="flex: 2;">
                    <input type="number" class="gen-percent" value="3" placeholder="%" style="flex: 1;">
                </div>
            </div>
            <div class="gen-level-row mb-2" data-level="3">
                <label style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">৩য় জেনারেশন — সেলস/পয়েন্ট ও বোনাস %</label>
                <div class="d-flex" style="gap: 8px;">
                    <input type="number" class="gen-sales" placeholder="সেলস (BV)" style="flex: 2;">
                    <input type="number" class="gen-percent" value="2" placeholder="%" style="flex: 1;">
                </div>
            </div>`;
        genLevelCount = 3;
        document.getElementById('gen-result').style.display = 'none';
    }
</script>
@endsection
