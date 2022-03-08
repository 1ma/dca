using DCA.Lib.Contracts;

namespace DCA.Lib.Bitstamp;

public class Withdrawer : IWithdrawer
{
    public bool Withdraw(Bitcoin amount, Address destination)
    {
        return true;
    }
}
