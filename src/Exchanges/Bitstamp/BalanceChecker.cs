using DCA.Model;
using DCA.Model.Contracts;

namespace DCA.Exchanges.Bitstamp;

public class BalanceChecker :  IBalanceChecker<Bitcoin>, IBalanceChecker<Dollar>, IBalanceChecker<Euro>
{
    Bitcoin IBalanceChecker<Bitcoin>.GetCurrentBalance()
    {
        return new Bitcoin();
    }

    Dollar IBalanceChecker<Dollar>.GetCurrentBalance()
    {
        return new Dollar();
    }

    Euro IBalanceChecker<Euro>.GetCurrentBalance()
    {
        return new Euro();
    }
}
